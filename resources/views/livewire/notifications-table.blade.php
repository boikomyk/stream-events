@inject('constants', '\App\Http\Controllers\DashboardController')

<div class="container mx-auto p-10">
    <h1 class="text-4xl font-semibold text-center mt-4 mb-6">{{__('Notifications for the last three months')}}</h1>

    <div class="tableFixHead">
    <table wire:loading.delay.class="opacity-50" class="table-auto w-full">
        <thead>
            <tr>
                <th class="px-4 py-2">{{__('Message')}}</th>
                <th class="px-2 py-2">{{__('Date')}}</th>
                <th class="px-2 py-2">{{__('Read')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notifications as $notification)
                <tr @if ($loop->last) id="last_record" @endif 
                    data-record-id={{$notification->record_id}}
                    data-record-type={{$notification->record_type}}
                    style="{{ 'background-color:' . ($notification->is_read ? 'grey' : 'white') }}"
                >
                    <td class="border px-4 py-4">
                        {{ $notification->message }}
                            @if ($notification->extra_message)
                                <p><i style="margin-left:2%;">"{{$notification->extra_message}}"</i></p>
                            @endif
                    </td>
                    <td class="border px-4 py-4">{{ $notification->created_at }}</td>
                    <td class="border px-2 py-2">
                        <input type="checkbox" name="readCheckbox" {{$notification->is_read ? 'checked' : ''}} />
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <hr/>
    <p class="text-black-800 font-bold my-10">Displayed records count {{$loadAmount}}/{{$totalRecords}}</p>

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

        $(function(){
            $("input[name='readCheckbox']").on('change', function(){
                let row_element = this.closest('tr')
                // $(row_element).css('background-color', $(this).is(':checked') ? 'grey' : 'white')

                // resolve params
                const record_id = $(row_element).data('record-id')
                const record_type = $(row_element).data('record-type')
                
                // create target url
                const target_url_template = "{{ route('dashboard.toogle-notification-read', [
                    $constants::RECORD_ID => '__RECORD_ID__',
                    $constants::RECORD_TYPE => '__RECORD_TYPE__'
                ]) }}"
                const target_url = target_url_template.replace('__RECORD_ID__', record_id).replace('__RECORD_TYPE__', record_type).replace(/&amp;/g, '&')

                // make req
                $.ajax({
                    type: "GET",
                    url: target_url,
                    async: false,
                    dataType: "json",
                    success: function (data) {
                        // mark column as read/unread
                        $(row_element).css('background-color', data.is_read ? 'grey' : 'white')
                    },
                    error: function (data) {
                        response = data.responseJSON;
                        console.log(response)
                        console.log("Failed with response: " + response.message)
                    }
                });
            })
        })
    </script>
</div>
