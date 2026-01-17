<script lang="ts">
    import { onMount, onDestroy } from 'svelte';
    import { useForm } from "@inertiajs/svelte";
    import { ScrollArea } from "@/components/ui/scroll-area";
    import { Plus, ArrowUp } from "lucide-svelte";
    import * as InputGroup from "@/components/ui/input-group/index.js";
    import Separator from '@/components/ui/separator/separator.svelte';
    import { fly } from 'svelte/transition';

    let { activeId, authId, chatName, messages = $bindable([]) } = $props();

    const form = useForm({ content: '' });

    onMount(() => {
        window.Echo.private(`chat.${activeId}`)
            .listen('.message.sent', async (e: any) => {
                if (!messages.find(m => m.id === e.message.id)) {
                    messages = [...messages, e.message];
                }
            });
    });

    function handleSendMessage(e: Event) {
        e.preventDefault();
        if (!$form.content.trim()) return;

        $form.post(`/chat/${activeId}/send`, {
            onSuccess: () => {
                $form.reset();
            },
        });
    }

    onDestroy(() => {
        window.Echo.leave(`chat.${activeId}`);
        console.log("the Chat window is DISTROIED");
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
                <div class="flex {msg.user_id === authId ? 'justify-end' : 'justify-start'}">
                    <div in:fly={{ y: 20, duration: 500 }} class="max-w-[70%] rounded-2xl px-4 py-2 text-sm shadow-sm
                                {msg.user_id === authId
                                    ? 'bg-primary text-primary-foreground rounded-tr-none'
                                    : 'bg-muted text-foreground rounded-tl-none'}"
                    >
                        <span class="text-[12px] p-0.5 pb-2 text-foreground opacity-70 block">{msg.user_name}</span>
                        {msg.content}
                    </div>
                </div>
            {/each}
        </div>
    </ScrollArea>
</div>

<form onsubmit={handleSendMessage} class="px-2 pb-2">
    <InputGroup.Root onsubmit={handleSendMessage}>
        <InputGroup.Input bind:value={$form.content} placeholder="Type message..." autocomplete="off" />
        <InputGroup.Addon align="block-end">
            <InputGroup.Button variant="outline" class="rounded-full" size="icon-xs">
                <Plus />
            </InputGroup.Button>
            <InputGroup.Text class="ms-auto">{$form.content.length} let</InputGroup.Text>
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
