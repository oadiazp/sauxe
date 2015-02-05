WSB.tpIoC = Ext.extend (WSB.UI.tpIoC, {
    initComponent: function(){
        WSB.tpIoC.superclass.initComponent.call (this);
        this.clickedNode = null;
        this.btnGenerate.setHandler(function(){this.newPackage();}, this);
        this.menuNewService.setHandler(function(){this.newService();}, this);
        this.menuComent.setHandler(function(){this.editDocblock();}, this);

        this.addListener('checkchange',function(){this.checkButtonsActivation();});
        this.addListener('contextmenu',function(node){this.showMenu(node);});

//        this.btnCreateDatatype.setHandler (function(){
//            this.wMngDatatype.show ()
//        }, this)
    },
    newPackage: function(){
        this.wNewPackage.show();
    },
    newService: function(){
        this.wNewService.show();
    },
    editDocblock: function(){
        var node = this.getChecked()[0];
        this.wDocblockEditor.loadDocblock(node.attributes.src,node.attributes.clas,node.attributes.method);
    },
    checkButtonsActivation: function(){
        if(this.getChecked().length){
            this.btnGenerate.enable();
        }
        else{
            this.btnGenerate.disable();
        }
    },
    showMenu: function(node){
        if (node.isLeaf() ) {
            node.select ();
            var selNodes = this.getChecked();
                Ext.each(selNodes, function(node){
                   node.ui.toggleCheck();
                });
            if(!node.attributes.checked){
                node.ui.toggleCheck();
            }
            this.clickedNode = node;
            this.menu.show (node.ui.getAnchor());
        }
    }

})