$(document).ready(function() {
    
        // print

       // $('#printButton').click(function() 
        $(document).on("click", "#printButton", function (){
            console.log("hi");
            var printContents = $('.modal-content').html();
            var originalContents = $('body').html();

            var printWindow = window.open('', '', 'height=500,width=800');
            printWindow.document.write('<html><head><title>Print Modal</title>');
            printWindow.document.write(
                '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">'
                );
            printWindow.document.write('</head><body>');
            printWindow.document.write(printContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        });
  });
  