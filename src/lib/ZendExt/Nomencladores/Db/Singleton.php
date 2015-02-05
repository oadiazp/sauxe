<?php
	class ZendExt_Nomencladores_Db_Singleton {
		private static $_db;
		
		public function getInstance () {
			if (self :: $_db == null)
				self :: $_db = new ZendExt_Nomencladores_Db_Concrete();
				
			return self :: $_db;
		}
	}
?>