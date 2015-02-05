/*
 * File: Analizador.vpCasos.ui.js
 * Date: Fri Jan 01 2010 00:00:49 GMT-1100 (Samoa Standard Time)
 * 
 * This file was generated by Ext Designer version 1.1.2.
 * http://www.sencha.com/products/designer/
 *
 * This file was manually exported.
 */

Analizador.UI.vpCasos = Ext.extend(Ext.Viewport, {
    layout: 'border',
    initComponent: function() {
        this.gpExcepciones = new Analizador.gpExcepciones ({
            scope: this
        });

        this.gpRendimiento = new Analizador.gpRendimiento ({
            scope: this
        });

        this.stTipos = new Ext.data.Store({
            url : 'tipos',
            autoLoad: true,
            reader: new Ext.data.JsonReader({
                root : 'data'
            }, ['tipo'])
        })

        this.cbTipos = new Ext.form.ComboBox ({
            store: this.stTipos,
            displayField: 'tipo',
            valueField: 'tipo',
            value : 'Excepciones',
            triggerAction : 'all',
            mode : 'local'
        })

        this.pGrids = new  Ext.Panel ({
            title: '',
            region: 'center',
            layout: 'card',
            activeItem: 0,
            items: [
                this.gpExcepciones,
                this.gpRendimiento
            ],
            tbar: [
                    {
                        text: 'Tipo: '
                    },
                    this.cbTipos
                ]
        })

        this.items = [
            {
                xtype: 'panel',
                title: 'Casos',
                region: 'center',
                layout: 'border',
                itemId: 'Casos',
                items: [
                    this.pGrids
                ]
            }
        ];
        Analizador.UI.vpCasos.superclass.initComponent.call(this);
    }
});