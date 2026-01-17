<script lang="ts">
    import * as Chart from '@/components/ui/chart/index.js';
    import * as Drawer from '@/components/ui/drawer/index.js';
    import { AreaChart } from 'layerchart';
    import { curveNatural } from 'd3-shape';
    import { scaleUtc } from "d3-scale";

    let { chartData } = $props();

    const chartConfig = {
        count: {
            label: "Messages",
            color: "var(--primary)",
        },
    } satisfies Chart.ChartConfig;
</script>

<Chart.Container config={chartConfig}>
    <AreaChart
        data={chartData}
        x="date"
        xScale={scaleUtc()}
        yDomain={[0, 100]} series={[
            {
                key: "count",
                label: "Messages",
                color: "var(--primary)",
            },
        ]}
        props={{
            area: {
                curve: curveNatural,
                "fill-opacity": 0.3,
                line: { class: "stroke-2" },
            },
            xAxis: {
                format: (v) => v.toLocaleDateString("en-US", {
                    day: 'numeric',
                    month: "short"
                }),

                ticks: 10,
            },
            yAxis: {
                ticks: 10,
            },
        }}
    >
        {#snippet tooltip()}
            <Chart.Tooltip
                indicator="dot"
                labelFormatter={(v: Date) => {
                    return v.toLocaleDateString("en-US", {
                        month: "numeric",
                        day: "numeric",
                        year: "numeric",
                    });
                }}
            />
        {/snippet}
    </AreaChart>
</Chart.Container>
