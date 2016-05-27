<?php
	class update{
		function update_database($sql){
			return $this->query($sql);
		}
	}