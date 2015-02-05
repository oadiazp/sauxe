/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


Analizador.vpCasos = Ext.extend (Analizador.UI.vpCasos, {
    cambiarGrid : function (pTipo) {
      if (pTipo == 'Excepciones') {
          this.pGrids.getLayout ().setActiveItem (0);
      } else {
        this.pGrids.getLayout ().setActiveItem (1);
      }
    },
    initComponent: function () {
        Analizador.vpCasos.superclass.initComponent.call (this)

        this.cbTipos.on ('select', function (c, r, i) {
            this.cambiarGrid (r.data.tipo)
        }, this)
    }
})

new Analizador.vpCasos ()