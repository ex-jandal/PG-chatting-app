<script lang="ts">
    import { onMount, onDestroy } from 'svelte';
    import { router, useForm } from "@inertiajs/svelte";
    import { ScrollArea } from "@/components/ui/scroll-area";
    import { Plus, ArrowUp, MoreHorizontal } from "lucide-svelte";
    import * as InputGroup from "@/components/ui/input-group/index.js";
    import * as ContextMenu from "@/components/ui/context-menu/index.js";
    import Separator from '@/components/ui/separator/separator.svelte';
    import { fly, fade } from 'svelte/transition';
    import Button from '@/components/ui/button/button.svelte';
    import InputGroupButton from '@/components/ui/input-group/input-group-button.svelte';

    let { activeId, authId, chatName, messages = $bindable([]) } = $props();

    const form = useForm({ content: '' });

    onMount(() => {
        let channel = window.Echo.private(`chat.${activeId}`);

        channel.listen('.message.sent', async (e: any) => {
            if (!messages.find(m => m.id === e.message.id)) {
                messages = [...messages, e.message];
            }
        });
        channel.listen('.message.delete', async (e: any) => {
            console.log(e.messageID);
            messages = [...messages.filter(m => m.id != e.messageID)];
            console.log(messages);
        });
    });

    function sendMessage(e: Event) {
        e.preventDefault();
        if (!$form.content.trim()) return;

        $form.post(`/chat/${activeId}/send`, {
            onSuccess: () => {
                $form.reset();
            },
        });
    }
    function deleteMessage(message: any) {
        router.delete(`/chat/${activeId}/delete/${message.id}`);
    }

    function formateDate(input: string): string {

        let date = new Date(input);
        let format = new Intl.DateTimeFormat('en-US', {
            hour: 'numeric',
            minute: 'numeric',
            month: 'short',
            day: 'numeric',
            hour12: true,
        }).format(date);
        return format.toString();
    }

    onDestroy(() => {
        window.Echo.leave(`chat.${activeId}`);
        // console.log("the Chat window is DISTROIED");
    });
</script>

<svelte:head>
    <title>PG - Chatting with {chatName}</title>
</svelte:head>

<div class="p-5.5 border-b font-semibold bg-card shrink-0">
    {chatName}
</div>

<div class="flex-1 overflow-hidden relative">
    <ScrollArea class="h-full w-full px-6">
        <div class="flex flex-col gap-2 py-10">
            {#each messages as msg}
                <div in:fly={{ y: 20, duration: 500 }} class="group">
                    <ContextMenu.Root>
                        <ContextMenu.Trigger class="flex {msg.user_id === authId ? 'justify-end' : 'justify-start'}">
                        <div class="flex flex-col wrap-break-word relative min-w-50 max-w-[70%] rounded-2xl px-4 py-2 text-md shadow-sm
                            {msg.user_id === authId
                                ? 'bg-primary text-primary-foreground rounded-tr-none'
                                : 'bg-muted text-foreground rounded-tl-none'}"
                        >
                                <button class="absolute shadow-sm rounded-full top-2 right-4 hidden group-hover:block">
                                    <MoreHorizontal class="w-6"/>
                                </button>
                            <span class="text-[12px] p-0.5 pb-2 text-foreground opacity-70 block">{msg.user_name}</span>
                            {msg.content}
                            <span class="text-[10px] text-end text-foreground block">{formateDate(msg.created_at)}</span>
                        </div>
                        </ContextMenu.Trigger>
                        <ContextMenu.Content class="w-52">
                            <ContextMenu.Item onclick={() => deleteMessage(msg)} inset>
                                Delete
                            </ContextMenu.Item>
                            <ContextMenu.Item inset disabled>
                                Edit
                            </ContextMenu.Item>
                            <ContextMenu.Item inset disabled>
                                Forward
                            </ContextMenu.Item>
                            <ContextMenu.Sub>
                                <ContextMenu.SubTrigger inset>More Tools</ContextMenu.SubTrigger>
                                <ContextMenu.SubContent class="w-48">
                                    <ContextMenu.Item disabled>Copy</ContextMenu.Item>
                                    <ContextMenu.Item>Create Shortcut...</ContextMenu.Item>
                                    <ContextMenu.Item>Name Window...</ContextMenu.Item>
                                    <ContextMenu.Separator />
                                    <ContextMenu.Item>Developer Tools</ContextMenu.Item>
                                </ContextMenu.SubContent>
                            </ContextMenu.Sub>
                        </ContextMenu.Content>
                    </ContextMenu.Root>
                </div>
            {/each}
        </div>
    </ScrollArea>
</div>

<form onsubmit={sendMessage} class="px-2 pb-2">
    <InputGroup.Root onsubmit={sendMessage}>
        <InputGroup.Input bind:value={$form.content} placeholder="Type message..." autocomplete="off" />
        <InputGroup.Addon align="block-end">
            <InputGroup.Button variant="outline" class="rounded-full" size="icon-xs">
                <Plus />
            </InputGroup.Button>
            <InputGroup.Text class="ms-auto text-[10px]">{$form.content.trim().length} let</InputGroup.Text>
            <Separator orientation="vertical" class="h-4!" />
            <InputGroup.Button
                variant="default"
                class="rounded-full"
                size="icon-xs"
                type="submit"
                disabled={$form.processing || !$form.content}
            >
                <ArrowUp />
                <span class="sr-only">Send</span>
            </InputGroup.Button>
        </InputGroup.Addon>
    </InputGroup.Root>
</form>
