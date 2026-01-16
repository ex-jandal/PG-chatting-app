<script lang="ts">
    import { onMount, onDestroy, tick } from 'svelte';
    import { useForm } from "@inertiajs/svelte";
    import { Button } from "@/components/ui/button";
    import { Input } from "@/components/ui/input";
    import { ScrollArea } from "@/components/ui/scroll-area";
    import { SendHorizontal } from "lucide-svelte";
    import { blur } from 'svelte/transition';

    let { activeId, authId, chatName, messages = $bindable([]) } = $props();

    const form = useForm({ content: '' });

    onMount(() => {
        // window.Echo.leaveAllChannels();

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
    });
</script>

<svelte:head>
    <title>PG - Chatting with {chatName}</title>
</svelte:head>

<main class="flex flex-col flex-1 bg-background overflow-hidden">
    <div class="p-5.5 border-b font-semibold bg-card shrink-0">
        {chatName}
    </div>

    <div class="flex-1 overflow-hidden relative">
        <ScrollArea class="h-full w-full px-6">
            <div class="flex flex-col gap-2 py-10">
                {#each messages as msg}
                    <div class="flex {msg.user_id === authId ? 'justify-end' : 'justify-start'}">
                        <div class="max-w-[70%] rounded-2xl px-4 py-2 text-sm shadow-sm
                                    {msg.user_id === authId
                                        ? 'bg-primary text-primary-foreground rounded-tr-none'
                                        : 'bg-muted text-foreground rounded-tl-none'}"
                        >
                            {msg.content}
                        </div>
                    </div>
                {/each}
            </div>
        </ScrollArea>
    </div>

    <div class="p-4 border-t bg-card shrink-0">
        <form onsubmit={handleSendMessage} class="flex gap-2">
            <Input bind:value={$form.content} placeholder="Type message..." autocomplete="off" />
            <Button type="submit" size="icon" disabled={$form.processing}>
                <SendHorizontal class="h-4 w-4" />
            </Button>
        </form>
    </div>
</main>
