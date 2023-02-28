<html>

<head>
    <meta name="viewport" content="width=1200, initial-scale=1">
    <title>Delivery Note</title>
    <meta http-equiv="Content-Type" content="text/html;" />
    <meta charset="UTF-8">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Lato', sans-serif;
            --print-font: 22px;
        }

        .row .col-xs-2 {
            padding-right: 0 !important;
            padding-left: 0 !important;
            line-height: 2.3 !important;
        }

        .row .col-xs-4 {
            padding-left: 0 !important;
            padding-right: 0 !important;
            line-height: 2.3 !important;
        }

        .headings {
            background: white;
        }

        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
    </style>
</head>

<body style="margin-bottom: 40px">

    <div style="display: flex; justify-content: center; margin-top: 5rem">
        <div id="outer_wrapper"
             style="width: 44.5mm;height: 14.5mm;background-color: white;border-color: transparent;border-radius: 1rem;display:flex;flex-direction: column;justify-content: center;align-items: center;box-shadow: 2px 2px 9px 4px #00000052, -2px -2px 3px 4px #00000052;">
            <div id="bar-code"></div>
            <div id="pin" style="font-size: 1rem;font-weight: bolder;color: black;">{{ $pin }}</div>
            <div id="imei" style="font-size: 1rem;font-weight: bolder;color: black;">{{ $imei }}</div>
        </div>
    </div>

    <div class="row mt-5">
        <div style="margin-top:15px; display:flex; justify-content: center;">
            <button class="btn btn-success" href="#" onclick="printLabel()"><i class="fa fa-print"></i> PRINT LABEL</button>
        </div>
    </div>

    <script src="{{ url('assets/js/jquery-barcode.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        $("#bar-code").barcode(
            "{{ $pin }}", // Value barcode (dependent on the type of barcode)
            "code39", // type (string)
            {
                "barWidth": 1.1,
                "barHeight": 20,
                fontSize: 12,
                showHRI: false,
            }
        );
    </script>

    <script>
        var mainWidth = 44.5;
        var mainHeight = 14.5;
        var mainRatio = mainWidth / mainHeight;

        function printLabel() {
            var element = document.getElementById("outer_wrapper");

            var opt = {
                margin: [0, 0],
                filename: 'label.pdf',
                image: {
                    type: 'png',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    x: 0,
                    y: 0,
                    scrollX: 0,
                    scrollY: 0,
                    windowWidth: mainWidth,
                    unit: "mm"
                },
                jsPDF: {
                    unit: 'mm',
                    format: [mainWidth, mainHeight],
                    orientation: 'l',
                    putOnlyUsedFonts: true,
                    pagesplit: true
                },
                pagebreak: {
                    after: ".my-print-page"
                }
            };
            html2pdf(element, opt);
        }
    </script>
</body>

</html>
