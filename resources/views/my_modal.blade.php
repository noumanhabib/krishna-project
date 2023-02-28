<div id="importModel{{ $grn->id }}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
                <table class="table" id="childTable">
                    <thead class="table-head">
                        <tr>
                            <th>Part</th>
                            <th>Model</th>
                            <th>Color</th>
                            <th>Quantity</th>
                            <th>SKU</th>
                        </tr>
                    </thead>o
                    <tbody>
                        @foreach ($grn->items as $item)
                            <tr>

                                <td>{{ \DB::table('part')->where('id', $item['part_id'])->first()->name }}</td>
                                <td>{{ \DB::table('model')->where('id', $item['model_id'])->first()->mname }}</td>
                                <td>{{ \DB::table('colour')->where('id', $item['color_id'])->first()->name }}</td>
                                <td> {{ $item['quantity'] }} </td>
                                <td>{{ $item['sku'] }}</td>
                            </tr>
                            {{-- <tr>
                                                                3
                                                            </tr> --}}
                        @endforeach
                        {{-- <tr>
                                                                <td>12345</td>
                                                                <td>123</td>
                                                                <td>334</td>

                                                            </tr> --}}
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>

</div>
