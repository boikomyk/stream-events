<div>
    <h4>{{ __('Summary for the past 30 days')}}<h4>
    <table>
        <tr>
            <th>Total revenue</th>
            <th>Followers</th>
            <th>Top 3 items</th>
        </tr>
    <tr>
        <td>{{ $summary_table->total_revenue_label }}</td>
        <td>{{ $summary_table->followers_amount }}</td>
        <td>
            @if (count($summary_table->top_items) > 0)
                <ul>
                    @foreach ($summary_table->top_items as $top_item)
                    <li>
                        <p>{{$loop->index +1}}) <em>{{ $top_item->name }}</em></p>
                        <b style="margin-left:10%;">{{ $top_item->total_price_label }}</b>
                    </li>
                    @endforeach
                </ul>  
            @else
                {{ __("Nothing has been sold in the last month.") }}
            @endif
        </td>
    </tr>
    </table> 
</div>
