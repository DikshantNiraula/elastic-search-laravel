@extends('layouts.app')

@section('content')
<div class="container">
    <div class="search-container">
        <input type="text" class="form-control" id="search-input" placeholder="Search...">
    </div>

    <h2>Search Results</h2>
    <ul id="search-results">
        <!-- Results will be displayed here -->
    </ul>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            var searchInput = $('#search-input');
            var searchResults = $('#search-results');

            searchInput.on('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Prevent the form from submitting (if applicable)

                    var query = searchInput.val().trim();
                    if (query.length === 0) {
                        searchResults.html(''); // Clear results if the input is empty
                        return;
                    }

                    $.ajax({
                        url: "{{ route('search') }}",
                        method: 'GET',
                        data: { query: query },
                        success: function (data) {
                            // Clear existing results
                         searchResults.html('');

                    // // Parse the JSON response, assuming it's an array of results
                    var results = data.hits.hits;
                    console.log(results);
                    if (results.length > 0) {
                        // Loop through the results and append them to the searchResults container
                        results.forEach(function (result) {
                            var listItem = $('<li>');
                            listItem.append($('<h3>').text(result._source.title));
                            listItem.append($('<p>').text(result._source.content));
                            searchResults.append(listItem);
                        });
                    } else {
                        searchResults.html('<p>No results found.</p>');
                    };
                        }
                    });
                }
            });
        });
    </script>
@endsection
