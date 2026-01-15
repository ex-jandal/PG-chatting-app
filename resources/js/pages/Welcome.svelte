<script lang="ts">
    import { Link, page, router } from '@inertiajs/svelte';
    import { blur } from 'svelte/transition';
    import { Button } from "@/components/ui/button";
    import { MessageCircleMore, MessageSquare, Zap, Shield, Sparkles, ArrowRight, Github } from "lucide-svelte";

    let user = $derived($page.props.auth.user);
</script>

<svelte:head>
    <title>PG Chatting App</title>
    <link rel="preconnect" href="https://rsms.me/" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
</svelte:head>

<div in:blur={{ duration: 500, delay: 400 }} out:blur={{ duration: 400 }} class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
    <header class="not-has-[nav]:hidden mb-6 w-full max-w-83.75ext-sm lg:max-w-4xl">
        <nav class="flex items-center justify-end gap-4">
            {#if user}
                <Link
                    as="button"
                    href={route('chat.index')}
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Chats
                </Link>
            {:else}
                <Link
                    as="button"
                    href={route('login')}
                    class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                >
                    Log in
                </Link>
                <Link
                    as="button"
                    href={route('register')}
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Register
                </Link>
            {/if}
        </nav>
    </header>

    <main class="flex-1 text-foreground">
        <section class="relative pt-24 pb-20 lg:pt-32 lg:pb-40 border-b overflow-hidden">
            <div class="container mx-auto px-4 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-muted border text-xs font-medium mb-8">
                    <span class="flex h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                    Now powered by Reverb
                </div>

                <MessageCircleMore class="h-20 w-20 text-5xl p-4 mx-auto mb-5 rounded-2xl bg-primary text-foreground" />

                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-8 leading-[1.1]">
                    Messaging that's <br />
                    <span class="text-transparent bg-clip-text bg-linear-to-r from-primary to-primary/60 italic">Pretty Good.</span>
                </h1>

                <p class="text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto mb-12 leading-relaxed">
                    The real-time chat experience you've been waiting for. Fast, secure, and built with the modern stack for people who value speed.
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <Button size="lg" onclick={() => router.get('chat')} class="h-14 px-8 text-md rounded-2xl group">
                        Start Chatting
                        <ArrowRight class="ml-2 h-4 w-4 group-hover:translate-x-1 transition-transform" />
                    </Button>
                    <Button size="lg" href="https://github.com/ex-jandal/PG-chatting-app" variant="outline" class="h-14 px-8 text-md rounded-2xl">
                        <Github/>
                        GitHub Repository
                    </Button>
                </div>

                <div class="mt-20 max-w-5xl mx-auto p-2 bg-muted/50 border rounded-4xl shadow-2xl overflow-hidden relative group">
                    <div class="bg-background rounded-3xl border overflow-hidden aspect-video flex items-center justify-center relative">
                        <div class="absolute inset-0 bg-grid-black/[0.02] -z-10"></div>
                        <div class="w-full h-full flex items-center justify-center">
                             <div class="flex flex-col gap-4 w-full max-w-sm p-6">
                                <div class="self-start bg-muted rounded-2xl rounded-tl-none p-4 text-sm w-[80%]">Hey, did you see the new Reverb integration?</div>
                                <div class="self-end bg-primary text-primary-foreground rounded-2xl rounded-tr-none p-4 text-sm w-[80%] shadow-md shadow-primary/20">Yeah! It's pretty good. ⚡</div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 ">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                    <div class="p-8 rounded-3xl border bg-background hover:border-primary/50 transition-colors group">
                        <Zap class="h-10 w-10 text-primary mb-6" />
                        <h3 class="text-xl font-bold mb-3">Reverb Fast</h3>
                        <p class="text-muted-foreground leading-relaxed">Sub-millisecond latency. Messages arrive before you can even blink.</p>
                    </div>

                    <div class="p-8 rounded-3xl border bg-background hover:border-primary/50 transition-colors group">
                        <Shield class="h-10 w-10 text-primary mb-6" />
                        <h3 class="text-xl font-bold mb-3">Secure Auth</h3>
                        <p class="text-muted-foreground leading-relaxed">Built on Laravel's hardened security. Your chats stay private, always.</p>
                    </div>

                    <div class="p-8 rounded-3xl border bg-background hover:border-primary/50 transition-colors group">
                        <Sparkles class="h-10 w-10 text-primary mb-6" />
                        <h3 class="text-xl font-bold mb-3">Modern UI</h3>
                        <p class="text-muted-foreground leading-relaxed">A "Pretty Good" interface using Shadcn and Tailwind CSS variables.</p>
                    </div>

                    <div class="p-8 rounded-3xl border bg-background hover:border-primary/50 transition-colors group">
                        <MessageSquare class="h-10 w-10 text-primary mb-6" />
                        <h3 class="text-xl font-bold mb-3">Group Ready</h3>
                        <p class="text-muted-foreground leading-relaxed">Start 1-on-1 chats or dynamic group conversations with ease.</p>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <footer class="border-t py-12 text-foreground">
        <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <MessageCircleMore class="h-5 w-5 text-primary" />
                <span class="font-bold">PG-Chatting-App</span>
            </div>
            <p class="text-sm text-muted-foreground">
                © 2026 PG-Chatting-App. Open source & Pretty Good.
            </p>
        </div>
    </footer>
</div>
