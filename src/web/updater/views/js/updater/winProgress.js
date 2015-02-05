Ext.ns ('Updater');
Ext.Ajax.method = 'post';

Updater.winProgress = Ext.extend(Ext.Window, {
    title: 'Actuazando ...',
    width: 352,
    height: 80,
    closable: false,
    modal: true,
    updateProgress : function (pValue, pText) {
        this.pb.updateProgress (pValue, pText)
    },
    initComponent: function() {
        this.pb = new Ext.ProgressBar ({value : 0})
        
        this.items = [this.pb];
        Updater.winProgress.superclass.initComponent.call(this);
    }
});
