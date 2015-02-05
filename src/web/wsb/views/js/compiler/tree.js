
Ext.ns ('AD')

AD.Tree = function (config) {

    if (config.url) {
        this.loader = new Ext.tree.TreeLoader({url: config.url,clearOnLoad:true})
    } else {
        this.loader = new Ext.tree.TreeLoader({url: 'load',clearOnLoad:true})
    }

    this.loader.on ('beforeload', function (tl, n) {
        if (tl.baseParams.type == null) {
            if (n.attributes.leaf)
                tl.baseParams.type = 'file';
            else
                tl.baseParams.type = 'folder';
        } else {
            tl.baseParams.type = n.attributes.type;
        }
       
        tl.baseParams.path = n.attributes.path;
        tl.baseParams.text = n.attributes.text;

        if (config['checkeable'])
            tl.baseParams.checkeable = config['checkeable'];

        if (config['only_folders'])
            tl.baseParams.only_folders = true;
    });
    
    //this.width = 300;
    this.autoScroll = true;
    
    this.root = new Ext.tree.AsyncTreeNode({text: 'Servidor Web',
                                            type: 'folder',
                                            leaf: false,
                                            id: 'root'}),

    AD.Tree.superclass.constructor.call (this, config)
}

Ext.extend (AD.Tree, Ext.tree.TreePanel);
Ext.reg ('ad.tree', AD.Tree);