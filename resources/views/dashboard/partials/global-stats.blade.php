<!-- resources/views/dashboard/partials/global-stats.blade.php -->

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    @include('dashboard.partials.global-chart')
    
    @include('dashboard.partials.global-calendar')
</div>
