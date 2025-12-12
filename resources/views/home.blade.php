@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="display-5 fw-bold mb-3">Welcome to the Dashboard</h1>
        <p class="mb-4 text-muted">
            Stay ahead with smart insights and stay connected to what matters most. Your centralized hub for managing
            contact submissions with ease.
        </p>

        <div class="row g-4">
            <!-- BidTrack Users Box -->
            <div class="col-md-6">
                <div onclick="window.location='{{ route('customers.index') }}'"
                    class="card text-white bg-warning h-100 shadow-sm"
                    style="cursor: pointer; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.02)'"
                    onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title fs-1 fw-bold">{{ $totalBidTrackUsers }}</h3>
                            <p class="card-text fs-5 mt-2">BidTrack Users</p>
                        </div>
                        <i class="fas fa-chart-line fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>

            <!-- TimeTrack Users Box -->
            <div class="col-md-6">
                <div onclick="window.location='{{ route('customers.index') }}'"
                    class="card text-white bg-success h-100 shadow-sm"
                    style="cursor: pointer; transition: transform 0.3s ease;"
                    onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title fs-1 fw-bold">{{ $totalTimeTrackUsers }}</h3>
                            <p class="card-text fs-5 mt-2">TimeTracks Users</p>
                        </div>
                        <i class="fas fa-clock fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>

            <!-- Other Users Box -->
            <div class="col-md-6">
                <div onclick="window.location='{{ route('customers.index') }}'"
                    class="card text-white bg-primary h-100 shadow-sm"
                    style="cursor: pointer; transition: transform 0.3s ease;"
                    onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title fs-1 fw-bold">{{ $otherUsers }}</h3>
                            <p class="card-text fs-5 mt-2">Other Users</p>
                        </div>
                        <i class="fas fa-user-friends fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>


            <!-- Total Users Box -->
            <div class="col-md-6">
                <div onclick="window.location='{{ route('customers.index') }}'"
                    class="card text-white bg-danger h-100 shadow-sm"
                    style="cursor: pointer; transition: transform 0.3s ease;"
                    onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title fs-1 fw-bold">{{ $totalUsers }}</h3>
                            <p class="card-text fs-5 mt-2">Total Users</p>
                        </div>
                        <i class="fas fa-user-circle fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>


        </div>

    </div>
@endsection
