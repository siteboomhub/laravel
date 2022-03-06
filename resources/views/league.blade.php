@extends('layout')

@section('content')
    <div class="container">
        <div class="row my-5">
            <div class="col-9">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td>
                            League Table
                        </td>
                        <td>
                            Match Results
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="width: 60%;">
                            <table class="table" id="teams">
                                <thead>
                                <tr>
                                    <td>Teams</td>
                                    <td>PTS</td>
                                    <td>P</td>
                                    <td>W</td>
                                    <td>D</td>
                                    <td>L</td>
                                    <td>GD</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr></tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="w-100">
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <div class="js-with-matches">
                                            <span class="js-current-week">0</span><sup>th</sup> Week Match Result
                                        </div>
                                        <div class="js-no-matches">
                                            Matches are not played yet
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table class="w-100 px-3" id="results">
                                            <tbody>
                                            <tr>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>
                            <button type="button" class="btn btn-dark" id="playAllBtn">Play all</button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-dark" id="playWeekBtn">Play this week</button>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-3">
                <table class="table table-bordered w-100">
                    <tr>
                        <td colspan="3" class="text-center">
                            <div class="js-with-matches">
                                <span class="js-current-week">0</span><sup>th</sup> Week Predictions Of Championship
                            </div>
                            <div class="js-no-matches">
                                Predictions Of Championship
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="table table-condensed w-100 px-3" id="predictions">
                                <tbody>
                                <tr>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <script>

        function getLeague() {
            $.get('/api/league/{{ request()->route()->parameter('leagueUUID') }}').then(function (league) {

                let teamsHtml = ''

                let predictionsHtml = ''

                league.teams.forEach(function (team) {
                    teamsHtml += "<tr>" +
                        "<td>" + team.name + "</td>" +
                        "<td>" + team.pts + "</td>" +
                        "<td>" + team.played + "</td>" +
                        "<td>" + team.won + "</td>" +
                        "<td>" + team.drawn + "</td>" +
                        "<td>" + team.lost + "</td>" +
                        "<td>" + team.gd + "</td>" +
                        "</tr>"

                    predictionsHtml += "<tr>" +
                        "<td>" + team.name + "</td>" +
                        "<td>%" + team.prediction_score + "</td>" +
                        "</tr>"
                })

                $('#teams').find('tbody').html(teamsHtml)
                $('#predictions').find('tbody').html(predictionsHtml)

                if (league.current_week > 0) {
                    $('.js-current-week').text(league.current_week)
                    $('.js-with-matches').show()
                    $('.js-no-matches').hide()

                    setMatchesResults(league.last_played_matches)
                } else {
                    $('.js-with-matches').hide()
                }

            }).catch(function (error) {
                alert(error.responseJSON.message)
            })
        }

        function playWeek(e) {
            e.preventDefault()

            $.post('/api/league/{{ request()->route()->parameter('leagueUUID') }}/play-week').then(function () {
                getLeague()
            }).catch(function (error) {
                alert(error.responseJSON.message)
            })
        }

        function playNextWeeks(e) {
            e.preventDefault()

            $.post('/api/league/{{ request()->route()->parameter('leagueUUID') }}/play-all-weeks').then(function () {
                getLeague()
            }).catch(function (error) {
                alert(error.responseJSON.message)
            })
        }

        function setMatchesResults(matches) {
            let resultsHtml = ''

            matches.forEach(function (match) {
                resultsHtml += "<tr>" +
                    "<td>" + match.first_team_name + "</td>" +
                    "<td>" + match.score.join('-') + "</td>" +
                    "<td>" + match.last_team_name + "</td>" +
                    "</tr>"
            })

            $('#results').find('tbody').html(resultsHtml)
        }

        $(document).ready(function () {
            getLeague()
            $('#playAllBtn').on('click', playNextWeeks)
            $('#playWeekBtn').on('click', playWeek)
        })
    </script>
@endsection
