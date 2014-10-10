	
			<script type="text/javascript" src="scripts/demos.js"></script>
			<script src="../controlador/funciones_procedimientos.js"></script>
			<script type="text/javascript" src="js/jquery.js"></script>
			<script src="js/jquery-1.9.1.min.js"></script>
			<script type="text/javascript" src="js/jquery.dataTables.js"></script>
			<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
                        <script src="js/alertify.js"></script>
                        <script src="js/jquery.numeric.js"></script>
                        <script src="js/validaciones.js"></script>
		<!--[if lt IE 10]>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="js/jquery.placeholder.min.js"></script>
		<![endif]-->		
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script src="js/sky-forms-ie8.js"></script>
		<![endif]-->
			
  <script type="text/javascript">	
      
    
		var oTable;
		var asInitVals = new Array();
		var CUENTAS = new Array();
          
(function($) {
			/*
			 * Function: fnGetColumnData
			 * Purpose:  Return an array of table values from a particular column.
			 * Returns:  array string: 1d data array 
			 * Inputs:   object:oSettings - dataTable settings object. This is always the last argument past to the function
			 *           int:iColumn - the id of the column to extract the data from
			 *           bool:bUnique - optional - if set to false duplicated values are not filtered out
			 *           bool:bFiltered - optional - if set to false all the table data is used (not only the filtered)
			 *           bool:bIgnoreEmpty - optional - if set to false empty values are not filtered from the result array
			 * Author:   Benedikt Forchhammer <b.forchhammer /AT\ mind2.de>
			 */
			$.fn.dataTableExt.oApi.fnGetColumnData = function ( oSettings, iColumn, bUnique, bFiltered, bIgnoreEmpty ) {
				// check that we have a column id
				if ( typeof iColumn == "undefined" ) return new Array();
				
				// by default we only want unique data
				if ( typeof bUnique == "undefined" ) bUnique = true;
				
				// by default we do want to only look at filtered data
				if ( typeof bFiltered == "undefined" ) bFiltered = true;
				
				// by default we do not want to include empty values
				if ( typeof bIgnoreEmpty == "undefined" ) bIgnoreEmpty = true;
				
				// list of rows which we're going to loop through
				var aiRows;
				
				// use only filtered rows
				if (bFiltered == true) aiRows = oSettings.aiDisplay; 
				// use all rows
				else aiRows = oSettings.aiDisplayMaster; // all row numbers
			
				// set up data array	
				var asResultData = new Array();
				
				for (var i=0,c=aiRows.length; i<c; i++) {
					iRow = aiRows[i];
					var aData = this.fnGetData(iRow);
					var sValue = aData[iColumn];
					
					// ignore empty values?
					if (bIgnoreEmpty == true && sValue.length == 0) continue;
			
					// ignore unique values?
					else if (bUnique == true && jQuery.inArray(sValue, asResultData) > -1) continue;
					
					// else push the value onto the result data array
					else asResultData.push(sValue);
				}
				
				return asResultData;
			}}(jQuery));
			
			//crea los filtro de select
			function fnCreateSelect( aData,num )
			{
			if (num != 0 ){
				var r='<select><option value=""></option>', i, iLen=aData.length;
				for ( i=0 ; i<iLen ; i++ )
				{
					r += '<option value="'+aData[i]+'">'+aData[i]+'</option>';
				}
				r+='</select>';
			}
				return r;
			}
			
			//eventos de la tabla
                        function llamaTodo(id){
           
			
				var oTable = $('#'+id).dataTable( {
					"bSortClasses": false,
					"sScrollY": "200px",
					"bPaginate": false,
					"bScrollCollapse": false
				} );
					
					//busqueda por input
				 $("tfoot input").keyup( function () {
					/* Filter on the column (the index) of this element */
					oTable.fnFilter( this.value, $("tfoot input").index(this) );
				} );
				 
     
				/*
				 * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
				 * the footer
				 */
				$("tfoot input").each( function (i) {
					asInitVals[i] = this.value;
				} );
				 
				 //sombre la fila
				$("tfoot input").focus( function () {
					if ( this.className == "search_init" )
					{
						this.className = "";
						this.value = "";
					}
				} );
				 
				$("tfoot input").blur( function (i) {
					if ( this.value == "" )
					{
						this.className = "search_init";
						this.value = asInitVals[$("tfoot input").index(this)];
					}
				} );
							
			
			//selecciona la fila por clic
					$("#"+id+" tbody tr").click( function( e ) {
					if ( $(this).hasClass('row_selected') ) {
						$(this).removeClass('row_selected');
					}
					else {
						oTable.$('tr.row_selected').removeClass('row_selected');
						$(this).addClass('row_selected');
					}
				});
            
			//eliminar
				$('#delete').click( function() {
					var anSelected = fnGetSelected( oTable );
					if ( anSelected.length !== 0 ) {
						oTable.fnDeleteRow( anSelected[0] );
					}
				} );
            	
				oTable = $('#'+id).dataTable();
			
				//ordena por campo
				/* Add a select menu for each TH element in the table footer */
				$("#pie"+id+" th").each( function ( i ) {
					//para poder usar el check
					if (i != 0 ){
						this.innerHTML = fnCreateSelect( oTable.fnGetColumnData(i),i);
					}
					$('select', this).change( function () {
						oTable.fnFilter( $(this).val(), i );
					} );
				} );
                                
                  		     /*
                                $('#example tr').click( function() {
					$(this).toggleClass('row_selected');
				} );
			
         *
         * */ 
                
			
                        
    };
                        
					//agrega registro
                        function fnClickAddRow() {
				$('#example').dataTable().fnAddData( [
					"1.1",
                                        "1.2",
                                        "1.3",
                                        "1.4",
                                        "1.5",
                                        '<input type="checkbox" name="check22" value="22"></td>']);
				
				
			}
                      //oculta columna  
                        function fnShowHide( iCol )
			{
				
				var oTable = $('#example').dataTable();
				
				var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
				oTable.fnSetColumnVis( iCol, bVis ? false : true );
			}
                        
                        
                        function fnGetSelected( oTableLocal )
			{
				return oTableLocal.$('tr.row_selected');
			}
                 
                        
		</script>
                
                
              