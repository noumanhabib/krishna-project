<body>

    <table>
        <tr>
            <td id="">
                {!! DNS1D::getBarcodeHTML('4445645656', 'UPCA') !!}
            </td>
        </tr>
        <tr>
            <td align="center">
                @php
                    echo $barcode;
                @endphp

            </td>

        </tr>
        <tr>
            <td align="center">
                @php
                    echo $sku;
                @endphp
            </td>
        </tr>
    </table>
</body>

<script>
    window.print();
</script>
