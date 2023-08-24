<div class="container mx-auto p-10">
    <h1 class="text-4xl font-semibold text-center mt-4 mb-6">{{__('Notifications')}}</h1>

    <div class="tableFixHead">
    <table wire:loading.delay.class="opacity-50" class="table-auto w-full">
        <thead>
            <tr>
                <th class="px-4 py-2">{{__('Name')}}</th>
                <th class="px-4 py-2">{{__('Email')}}</th>
                <th class="px-4 py-2">{{__('Created at')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notifications as $notification)
                <tr @if ($loop->last) id="last_record" @endif>
                    <td class="border px-4 py-4">{{  random_int(100000, 999999) }}</td>
                    <td class="border px-4 py-4">{{ 3 }}</td>
                    <td class="border px-4 py-4">{{ 4 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <hr/>
    <p class="text-black-800 font-bold my-10">Displayed records count {{$loadAmount}}</p>

    @if ($loadAmount >= $totalRecords)
        <p class="text-gray-800 font-bold text-2xl text-center my-10">No Remaining Records!</p>
    @endif

    <script>
        const lastRecord = document.getElementById('last_record');
        const options = {
            root: null,
            threshold: 1,
            rootMargin: '0px'
        }
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    @this.loadMore()
                }
            });
        });
        observer.observe(lastRecord);

        // or it can be done alternatively by directly using an ajax request to the BE endpoint  
        // or example with page and offset parameters, and then simply appending the new results to the table
        // as new childs (without using livewire functionality)

        // for ex. something like this
        // function load_more(page){
        //     $.ajax({
        //     url: 'some_url?page=" + page,
        //     type: "get",
        //     datatype: "html",
        //     beforeSend: function()
        //     {
        //         $('.ajax-loading').show();
        //     }
        //     })
        //     .done(function(data)
        //     {          
        //         if(data.length == 0){
        //             $('.ajax-loading').html("No more records!");
        //             return;
        //         }
        //         $('.ajax-loading').hide();
        //         $("#results").append(data);
        //     })
        //     .fail(function(jqXHR, ajaxOptions, thrownError)
        //     {
        //         alert('No response from server');
        //     });
        // }
    </script>
</div>
