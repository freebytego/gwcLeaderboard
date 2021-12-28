<?php 

function returnColor($color_id) {
	switch ($color_id) {
		case 0:
			return "#ff0d00";
			break;
		case 1:
			return "#0032ff";
			break;
		case 2:
			return "#68ff00";
			break;
		case 3:
			return "#ffe202";
			break;
		case 4:
			return "#fc8800";
			break;
		case 5:
			return "#9700ff";
			break;
		case 6:
			return "#01adff";
			break;
		case 7:
			return "#ff6fe8";
			break;
		case 8:
			return "#ffffff";
			break;
		case 9:
			return "#56eda0";
			break;
		case 10:
			return "#800000";
			break;
		case 11:
			return "#008001";
			break;
		case 12:
			return "#7d4200";
			break;
		case 13:
			return "#565656";
			break;
		case 14:
			return "#161616";
			break;		
		default:
			return "#ff0d00";
			break;
	}
}

function returnModel($model_id) {
	switch ($model_id) {
		case 0:
			return "cars/default.png";
			break;
		case 1:
			return "cars/miata.png";
			break;
		case 2:
			return "cars/og.png";
			break;
		case 3:	
			return "cars/f1.png";
			break;					
		case 4:
			return "cars/polonez.png";			
			break;
		case 5:
			return "cars/posh.png";			
			break;
		case 6:
			return "cars/drip.png";
			break;
		default:
			return "cars/default.png";
			break;
	}
}
 
?>