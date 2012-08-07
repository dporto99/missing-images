/*!
 * Ext JS Library 3.3.1
 * Copyright(c) 2006-2010 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
Ext.onReady(function(){
	
	Ext.QuickTips.init();
	Ext.form.Field.prototype.msgTarget = 'qtip';

  var frm_excursions = new Ext.form.FormPanel({
    renderTo: 'tabs',
    title:'Excursion Differences between Staging and Production.',
    labelAlign: 'top',
    anchor: '98%',
    height:630,
    width: 1600,
    frame:false,
    autoScroll:true,
    xtype:'fieldset',
    items:[grid_excursions]
  })

});

