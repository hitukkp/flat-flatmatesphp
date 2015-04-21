<?php
echo $this->Html->Script(array('jquery-1.8.2.min', 'jquery.dataTables.min', 'jquery.dataTables.columnFilter'));
echo $this->Html->css('jquery.dataTables');
?>
<script>
    var dataSet = <?php echo $posts; ?>;
    //console.log(dataSet);

    var d = new Date();
    
    var month = new Array();

    month[0] = "January";
    month[1] = "February";
    month[2] = "March";
    month[3] = "April";
    month[4] = "May";
    month[5] = "June";
    month[6] = "July";
    month[7] = "August";
    month[8] = "September";
    month[9] = "October";
    month[10] = "November";
    month[11] = "December";

    var m = month[d.getMonth()];

    var y = d.getFullYear();

    $(document).ready(function() {
        
        var table = $('#example').dataTable( {
            "aaData": dataSet,
            "iDisplayLength": 20,
            "aLengthMenu": [[10, 20, 30, 50, 100, 200, -1], [10, 20, 30, 50, 100, 200, "All"]],
            "scrollY": "200px",
            "paging": false,
            "aoColumns": [{
                "mData":"fieldName"             
              },{
                "mData":"year"
              },{
                "mData": "month",
                "sDefaultContent":"October"
              },{
                "mData": "mobileWeb",
                "mRender": function(data, type, full) {
                    return ( data )
                    ? data
                    : "N/A" ;
                }
              },{
                "mData": "desktop",
                "mRender": function(data, type, full) {
                    return ( data )
                    ? data
                    : "N/A" ;
                }
              },{
                "mData": "excApp",
                "mRender": function(data, type, full) {
                    return ( data )
                    ? data
                    : "N/A" ;
                }
              },{
                "mData": "gulf",
                "mRender": function(data, type, full) {
                    return ( data )
                    ? data
                    : "N/A" ;
                }
              },{
                "mData": "india",
                "mRender": function(data, type, full) {
                    return ( data )
                    ? data
                    : "N/A" ;
                }
              },{
                "mData": "logedinFree",
                "mRender": function(data, type, full) {
                    return ( data )
                    ? data
                    : "N/A" ;
                }
              },{
                "mData": "logedinPaid",
                "mRender": function(data, type, full) {
                    return ( data )
                    ? data
                    : "N/A" ;
                }
              },{
                "mData": "logedinTotal",
                "mRender": function(data, type, full) {
                    return ( data )
                    ? data
                    : "N/A" ;
                }
              },{
                "mData": "logedout",
                "mRender": function(data, type, full) {
                    return ( data )
                    ? data
                    : "N/A" ;
                }
              },{
                "mData": "mobileApp",
                "mRender": function(data, type, full) {
                    return ( data )
                    ? data
                    : "N/A" ;
                }
              },{
                "mData": "mobileAppWeb",
                "mRender": function(data, type, full) {
                    return ( data )
                    ? data
                    : "N/A" ;
                }
              },{
                "mData": "other",
                "mRender": function(data,    type, full) {
                    return ( data )
                    ? data
                    : "N/A" ;
                }
              },{
                "mData": "total",
                "mRender": function(data, type, full) {
                    return ( data )
                    ? data
                    : "N/A" ;
                }
              }]
        } ).columnFilter({
            aoColumns: [ { type: "text" },
                         { type: "text" ,selected: y },
                         { type: "select" , values: [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'], selected: m },
                         { type: "text" },
                         { type: "text" },
                         { type: "text" },
                         { type: "text" },
                         { type: "text" },
                         { type: "text" },
                         { type: "text" },
                         { type: "text" },
                         { type: "text" },
                         { type: "text" },
                         { type: "text" },
                         { type: "text" },
                         { type: "text" }]
        });

        $('a.toggle-vis').on( 'click', function (e) {
            e.preventDefault();

            // Get the column API object
            var column = table.column( $(this).attr('data-column') );

            // Toggle the visibility
            column.visible( ! column.visible() );
        } );

    } );
    

</script>

<body>

<table id="example" class="display" cellspacing="0" width="100%">
<thead>
    <tr>
        <th>Field Name</th>
        <th>Year</th>
        <th>Month</th>
        <th>Mobile Web</th>
        <th>Desktop</th>
        <th>Exc App</th>
        <th>Gulf</th>
        <th>India</th>
        <th>Logged In Free</th>
        <th>Logged In Paid</th>
        <th>Logged In Total</th>
        <th>Logged Out</th>
        <th>Mobile App</th>
        <th>Mobile Web App</th>
        <th>Other</th>
        <th>Total</th>
    </tr>
</thead>
<tfoot>
    <tr>
        <th>Field Name</th>
        <th>Year</th>
        <th>Month</th>
        <th>Mobile Web</th>
        <th>Desktop</th>
        <th>Exc App</th>
        <th>Gulf</th>
        <th>India</th>
        <th>Logged In Free</th>
        <th>Logged In Paid</th>
        <th>Logged In Total</th>
        <th>Logged Out</th>
        <th>Mobile App</th>
        <th>Mobile Web App</th>
        <th>Other</th>
        <th>Total</th>
    </tr>
</tfoot>
<tbody>
</tbody>
</table>
</body>