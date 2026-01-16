<script lang="ts">
    import NavFooter from '@/components/NavFooter.svelte';
    import NavMain from '@/components/NavMain.svelte';
    import NavUser from '@/components/NavUser.svelte';
    import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
    import { type NavItem } from '@/types';
    import { inertia, Link, router } from '@inertiajs/svelte';
    import { Github, LayoutGrid, List, Earth } from 'lucide-svelte';
    import AppLogo from './AppLogo.svelte';

    const mainNavItems: NavItem[] = [
        {
            title: 'Chats',
            href: '/chat',
            icon: List,
        },
        {
            title: 'Dashboard',
            href: '/admin/dashboard',
            icon: LayoutGrid,
        },
    ];

    const footerNavItems: NavItem[] = [
        {
            title: 'PG Repo',
            href: 'https://github.com/ex-jandal/PG-chatting-app',
            icon: Github,
        },
        {
            title: 'My Portfolio',
            href: 'https://me.abujandal.qzz.io',
            icon: Earth,
        },
    ];
</script>

<Sidebar collapsible="icon" variant="inset">
    <SidebarHeader>
        <SidebarMenu>
            <SidebarMenuItem>
                <SidebarMenuButton size="lg">
                    <Link href='/chat' viewTransition>
                        <AppLogo />
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarHeader>

    <SidebarContent>
        <NavMain items={mainNavItems} />
    </SidebarContent>

    <SidebarFooter>
        <NavFooter items={footerNavItems} class="mt-auto" />
        <NavUser />
    </SidebarFooter>
</Sidebar>
