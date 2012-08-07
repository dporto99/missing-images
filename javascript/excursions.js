Ext.namespace('excursions')

//////////////DATASTORES////////////////
var datastore_excursions = new Ext.data.Store({
	proxy: new Ext.data.HttpProxy({
		url:'php/Interface.php',
		method:'POST'
	}),
	baseParams: {task: "load_excursions"},
	reader: new Ext.data.JsonReader({
		root:'results',
		totalProperty:'total'
	},[
		{name: 'code', type: 'string', mapping:'code'},
    {name: 'type', type: 'string', mapping:'type'},
    {name: 'excursion', type: 'string', mapping:'excursion_image'},
		{name: 'small', type: 'string', mapping:'small_image'},
    {name: 'large', type: 'string', mapping:'large_images'},
    {name: 'stg_excursion', type: 'string', mapping:'stg_excursion_image'},
    {name: 'stg_small', type: 'string', mapping:'stg_small_image'},
    {name: 'stg_large', type: 'string', mapping:'stg_large_images'}
	   ])
});
/////////////////END DATASTORES//////////////
var btn_load_data = new Ext.Button({
  text: 'Load Data',
  handler: function(){
    datastore_excursions.load();
  }
})

var btn_export_to_html = new Ext.Button({
  text: 'Export to HTML',
  handler: function(){
    export_to_html();
  }
})
/////////////GRIDS///////////////////////////
var sm_excursions = new Ext.grid.CheckboxSelectionModel({
	singleSelect: true
});
var grid_excursions = new Ext.grid.GridPanel({
	store: datastore_excursions,
	selModel: sm_excursions,
    cm: new Ext.grid.ColumnModel([
    	{header: "Code", width: 60, sortable: true, dataIndex: 'code'},
      {header: "Type", width: 80, sortable: true, dataIndex: 'type'},
      {header: "Production Small Image", width: 220, sortable: true, dataIndex: 'small'},
      {header: "Staging Small Image", width: 220, sortable: true, dataIndex: 'stg_small'},
		  {header: "Production Large Image", width: 220, sortable: true, dataIndex: 'large'},
      {header: "Staging Large Image", width: 220, sortable: true, dataIndex: 'stg_large'},
      {header: "Production Excursion Image", width: 265, sortable: true, dataIndex: 'excursion'},
      {header: "Staging Excursion Image", width: 265, sortable: true, dataIndex: 'stg_excursion'}
	]),
	height:600,
	frame:true,
	enableColumnMove: true,
	enableColumnHide: false,
  tbar: [btn_load_data,'-',btn_export_to_html],
	viewConfig: {
    //Return CSS class to apply to rows depending upon data values
    getRowClass: function(record, index) {
      var small = record.get('small');
      var large = record.get('large');
      var stg_small = record.get('stg_small');
      var stg_large = record.get('stg_large');

      if (!small && !large) {
        return 'no-file';
      }
      else if (!stg_small && !stg_large){
        return 'no-staging';
      }
    }

	}
});
	
	////////////END GRIDS///////////////////////

function export_to_csv(){
  Ext.Ajax.request({
    waitMsg: 'Wait please...',
    url: 'php/Interface.php',
    params: {
      task: "export_to_csv"
    },
    success: function(response){
      var result=eval(response.responseText);
      switch(result){
        case 1:
          Ext.MessageBox.alert('OK','CSV file was successfully created.');
          break;
        case 0:
          Ext.MessageBox.alert('ERROR','Could not create CSV file.');
          break;
      }
    },
    failure: function(response){
      var result=response.responseText;
      Ext.MessageBox.alert('ERROR','ERROR.');
    }
  });
}

function export_to_html(){
  Ext.Ajax.request({
    waitMsg: 'Wait please...',
    url: 'php/Interface.php',
    params: {
      task: "export_to_html"
    },
    success: function(response){
      var result=eval(response.responseText);
      switch(result){
        case 1:
          Ext.MessageBox.alert('OK','HTML file was successfully created.');
          break;
        case 0:
          Ext.MessageBox.alert('ERROR','Could not create HTML file.');
          break;
      }
    },
    failure: function(response){
      var result=response.responseText;
      Ext.MessageBox.alert('ERROR','ERROR.');
    }
  });
}
