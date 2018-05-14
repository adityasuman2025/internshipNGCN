<!DOCTYPE html>
<html>
<head>
	<title>table</title>
		
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.debug.js"></script>
	<script type="text/javascript" src="js/html2canvas.js"></script>
<!-- 
	<script type="text/javascript">
		function genPDF() {
	
			var doc = new jsPDF();
			
		    var specialElementHandlers = {
		        '#hidediv' : function(element,render) {return true;}
		    };
		    
		    doc.fromHTML($('#table_id').get(0), 20,20,{
		                 'width':1000,
		        		'elementHandlers': specialElementHandlers
		    });
			
			doc.save('Test.pdf');
			
		}

		function genPDF()
		{
			html2canvas(document.getElementById("table_id"),
			{
				onrendered: function(canvas)
				{
					var img = canvas.toDataURL("image/png");
					var doc = new jsPDF();
					doc.addImage(img, 'JPEG',20,20);
					doc.save('test.pdf');
				}
			});
		}
	</script> -->
	 <script type="text/javascript">
	     function generatePDF() {
	        window.scrollTo(0, 0);
	 
	        var pdf = new jsPDF('p', 'pt', [2000, 1100]);
	 
	        html2canvas($("#table_id")[0], {
	            onrendered: function(canvas) {
	                //document.body.appendChild(canvas);
	                var ctx = canvas.getContext('2d');
	                var imgData = canvas.toDataURL("image/png", 1.0);
	                var width = canvas.width;
	                var height = canvas.clientHeight;
	                pdf.addImage(imgData, 'PNG', 10, 10, (width - 10), (height));
	 				pdf.save('test.pdf');
	            }
	        });
	    }
    </script>
</head>
<body>
	<table id="table_id" class="display">
        <tr>
            <th>Column 1</th>
            <th>Column 2</th>
             <th>Column 1</th>
            <th>Column 2</th>
             <th>Column 1</th>
            <th>Column 2</th>
             <th>Column 1</th>
            <th>Column 2</th>
             <th>Column 1</th>
            <th>Column 2</th>
        </tr>
        <tr>
            <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
             <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
             <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
             <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
             <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
        </tr>
        <tr>
            <td>Row 2 Data 1</td>
            <td>Row 2 Data 2</td>
             <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
             <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
             <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
             <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
        </tr>
	</table>
	<button id="pdf" onclick="javascript:generatePDF()">pdf</button>


	<!-- <script type="text/javascript">
        function demoFromHTML() {
            var pdf = new jsPDF('p', 'pt', 'letter');
            // source can be HTML-formatted string, or a reference
            // to an actual DOM element from which the text will be scraped.
            source = $('#table_id')[0];

            // we support special element handlers. Register them with jQuery-style 
            // ID selector for either ID or node name. ("#iAmID", "div", "span" etc.)
            // There is no support for any other type of selectors 
            // (class, of compound) at this time.
            specialElementHandlers = {
                // element with id of "bypass" - jQuery style selector
                '#bypassme': function(element, renderer) {
                    // true = "handled elsewhere, bypass text extraction"
                    return true
                }
            };
            margins = {
                top: 80,
                bottom: 60,
                left: 40,
                width: 522
            };
            // all coords and widths are in jsPDF instance's declared units
            // 'inches' in this case
            pdf.fromHTML(
                    source, // HTML string or DOM elem ref.
                    margins.left, // x coord
                    margins.top, {// y coord
                        'width': margins.width, // max width of content on PDF
                        'elementHandlers': specialElementHandlers
                    },
            function(dispose) {
                // dispose: object with X, Y of the last line add to the PDF 
                //          this allow the insertion of new lines after html
                pdf.save('Test.pdf');
            }
            , margins);
        }
    </script> -->


   
</body>
</html>