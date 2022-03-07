@extends('layout')

@section('content')
    <form class="d-flex align-items-center my-auto" action="" id="leagueForm" style="height: 100vh">
        @csrf
        <div class="container shadow-sm rounded p-4 pb-5">
            <div class="row">
                <h1 class="text-center mb-5">Premier League</h1>
            </div>
            <div class="row align-items-center">
                <div class="col-4">
                    <label for="">
                        Matches per week
                        <input class="form-control" type="number" name="games_number_per_week" value="2">
                    </label>
                </div>
                <div class="col-4">
                    <label for="">
                        Amount of teams
                        <input class="form-control" type="number" name="teams_number" value="4">
                    </label>
                </div>
                <div class="col-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success flex-1">
                        Create new Premier League
                    </button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function () {
            $('#leagueForm').on('submit', function (e) {
                e.preventDefault()

                $.post('api/league', $(this).serialize()).then(function (link) {
                    window.location = link
                }).catch(function (error) {
                    alert(error.responseJSON.message)
                })

            })
        })
    </script>
@endsection
