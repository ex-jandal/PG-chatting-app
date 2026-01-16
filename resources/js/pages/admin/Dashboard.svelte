<script lang="ts">
    import * as Card from "@/components/ui/card/index.js";
    import { Badge } from "@/components/ui/badge/index.js";
    import AppLayout from '@/layouts/AppLayout.svelte';
    import { type BreadcrumbItem } from '@/types';
    import { Link } from '@inertiajs/svelte';
    import { buttonVariants } from '@/components/ui/button';
    import MessageDrawer from '@/components/MessageDrawer.svelte';
    import { onMount } from 'svelte';

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Admin Dashboard',
            href: '/admin/dashboard',
        },
    ];

    interface IHistroy {
        date: Date | string,
        count: string
    }

    interface Props {
        stats: {
            totalUsers: string,
            totalMessages: string,
            histroy: IHistroy[],
        };
    }

    let { stats }: Props = $props();

    let formattedChartData: any = $state([]);
    onMount(() => {
        formattedChartData = stats.histroy.map(item => ({
            date: new Date(item.date),
            count: Number.parseInt(item.count)
        }));
    })

</script>

<svelte:head>
    <title>PG - Dashboard</title>
</svelte:head>

<AppLayout {breadcrumbs}>
    <div class="space-y-4 px-4 pt-4 overflow-x-auto">
        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
            <Card.Root class="@container/card">
                <Card.Header>
                    <Card.Description>Total Users</Card.Description>
                    <Card.Title class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                        {stats.totalUsers}
                    </Card.Title>
                    <Card.Action>
                        <Link
                            href="/admin/users" viewTransition
                            class={buttonVariants({ variant: "ghost", size: "sm" })}
                        >
                            View All
                        </Link>
                    </Card.Action>
                </Card.Header>
                <Card.Footer class="flex flex-col items-start gap-3 text-sm">
                    <div class="line-clamp-1 flex gap-2 font-medium text-muted-foreground">
                        All Users from `users` table.
                    </div>
                    <Link
                        href="/admin/users" viewTransition
                        class="w-full {buttonVariants({ variant: 'outline', size: 'sm' })}"
                    >
                        Manage User List
                    </Link>
                </Card.Footer>
            </Card.Root>
            <Card.Root class="@container/card py-10 flex flex-col justify-between">
                <Card.Header>
                    <Card.Description>Recent Messages</Card.Description>
                    <Card.Title class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                        {stats.totalMessages}
                    </Card.Title>
                    <Card.Action>
                        {#if Number.parseInt(stats.totalMessages) > 0}
                            <Badge variant="default" class="bg-foreground text-background animate-pulse">
                                New
                            </Badge>
                        {/if}
                    </Card.Action>
                </Card.Header>

                <Card.Footer class="flex flex-col items-start gap-3 text-sm">
                    <div class="line-clamp-1 flex gap-2 font-medium text-muted-foreground">
                        Messages from the contact form
                    </div>
                    <!---->
                    <!-- <Link -->
                    <!--     href="/admin/messages" -->
                    <!--     class="w-full {buttonVariants({ variant: 'outline', size: 'sm' })}" -->
                    <!-- > -->
                    <!--     Open Inbox -->
                    <!-- </Link> -->
                </Card.Footer>
            </Card.Root>
        </div>
        <div class="relative h-[calc(100vh-21rem)] overflow-scroll">
            <Card.Root class="relative p-8 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <Card.Title class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                    Message Chart
                </Card.Title>
                <Card.Description>Showing all messages in last 6 months.</Card.Description>
                <MessageDrawer chartData={formattedChartData}/>
            </Card.Root>
        </div>
    </div>
</AppLayout>
