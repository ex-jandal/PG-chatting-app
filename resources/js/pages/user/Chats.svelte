<script lang="ts">
    import AppLayout from "@/layouts/AppLayout.svelte";
    import type { BreadcrumbItem } from '@/types';
    import { router } from "@inertiajs/svelte";
    import { Button } from "@/components/ui/button";
    import { ScrollArea } from "@/components/ui/scroll-area";
    import * as Avatar from "@/components/ui/avatar";
    import * as Dialog from "@/components/ui/dialog";
    import { Plus } from "lucide-svelte";
    import ChatWindow from "./ChatWindow.svelte";

    let {
        conversations = [],
        messages = $bindable([]),
        allUsers = [],
        activeId = null,
        authId = null
    } = $props();

    let isNewChatModalOpen = $state(false);

    function startNewChat(userId: number) {
        router.post('/chat/create', { user_id: userId }, {
            onSuccess: () => isNewChatModalOpen = false
        });
    }


    // Reactively find the name of the person you are chatting with
    let activeChat = $derived(conversations.find(c => c.id === activeId));


    const breadcrumbs: BreadcrumbItem[] = (activeId) ? [
            {
                title: 'Chats',
                href: '/chat'
            },
            {
                title: `${activeChat.chat_name}`,
                href: `/chat/${activeId}`
            }
        ]
            :
        [
            {
                title: 'Chats',
                href: '/chat'
            },
        ];
</script>

<AppLayout {breadcrumbs}>
    <Dialog.Root bind:open={isNewChatModalOpen}>
        <Dialog.Content>
            <Dialog.Header>
                <Dialog.Title>Start New Chat</Dialog.Title>
            </Dialog.Header>
            <ScrollArea class="max-h-80 pr-4">
                <div class="space-y-2">
                    {#each allUsers as user}
                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-accent transition-colors">
                            <span class="font-medium">{user.name}</span>
                            <Button size="sm" onclick={() => startNewChat(user.id)}>Message</Button>
                        </div>
                    {/each}
                </div>
            </ScrollArea>
        </Dialog.Content>
    </Dialog.Root>

    <div class="flex h-[calc(100vh-73px)] md:h-[calc(100vh-88px)] overflow-hidden mt-2 md:rounded-lg bg-background">
        <aside class="lg:w-80 w-18 border-r flex flex-col bg-card/50">
            <div class="p-4 border-b flex justify-between items-center bg-card">
                <h2 class="text-xl hidden lg:block font-bold">Chats</h2>
                <Button size="icon" variant="outline" onclick={() => isNewChatModalOpen = true}>
                    <Plus class="h-4 w-4" />
                </Button>
            </div>

            <ScrollArea class="flex-1 p-1">
                {#each conversations as chat}
                    <button
                        onclick={() => {
                            router.get(`/chat/${chat.id}`)
                        }}
                        class="w-full mb-1 flex items-center gap-3 p-4 transition-all hover:bg-accent hover:rounded-lg {activeId === chat.id ? 'bg-accent border-r-4 rounded-l-lg border-primary' : ''}"
                    >
                        <Avatar.Root>
                            <Avatar.Fallback class="text-foreground">{chat.chat_name[0]}</Avatar.Fallback>
                        </Avatar.Root>
                        <div class="flex-1 text-left truncate font-medium">
                            {chat.chat_name}
                        </div>
                    </button>
                {/each}
            </ScrollArea>
        </aside>

        {#if activeId}
            {#key activeId}
                <ChatWindow
                    {activeId}
                    {authId}
                    bind:messages
                    chatName={activeChat?.chat_name}
                />
            {/key}
        {:else}
            <main class="flex-1 flex flex-col items-center justify-center text-muted-foreground gap-4">
                <p>Select a chat to start messaging</p>
                <Button variant="secondary" onclick={() => isNewChatModalOpen = true}>Start New Conversation</Button>
            </main>
        {/if}
    </div>
</AppLayout>
