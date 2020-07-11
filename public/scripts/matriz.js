/**
 * DEFINICION DE CLASE
 */
export class Matriz {
	constructor(){
		this.rangos = [];
		this.combinaciones = [];
	}
    range(ini,fin){
		var a = new Array();
		// console.log('ini:'+ ini);
		// console.log('ini:'+ fin);
        for(var i = ini; i <= fin; i++){
			a.push(i);
            // console.log('i:'+ a);  
        }
        return a;       
	}
	setRangos(rangos){
		for (let index = 0; index < rangos.length; index++) {
			const element = rangos[index];
			this.rangos.push(this.range(element['x'], element['y']));
		}		
	}

	estaEnSector(x,y){
		console.log("estaEnSector: ");
		var rangoX, rangoY, coordenadasXeY;
		for (let index = 0; index < this.rangos.length; index++) {
			const element = this.rangos[index];
			console.debug(element);
			if (element.indexOf(x)!=-1){
				console.log("encontre x en rango: (" + element[0] + ":"+ element[element.length-1] + ")");	
				rangoX = '('+ element[0] +','+ element[element.length-1] +')';
			}
			if (element.indexOf(y)!=-1){
				console.log("encontre y en rango: (" + element[0] + ":" + element[element.length-1] + ")");	
				rangoY = '('+ element[0] +','+ element[element.length-1] +')';
			}
		}
		coordenadasXeY = rangoX + ':' + rangoY;
		console.log(coordenadasXeY);
		console.log('sector tocado: ' + this.combinaciones[coordenadasXeY]);
		return this.combinaciones[coordenadasXeY];
	}
	setCombinaciones(combinaciones){
		this.combinaciones = combinaciones;
	}
	getRangos(){
		return this.rangos;
	}
	getCombinaciones(){
		return this.combinaciones;
	}
	getRangos(){
		return this.rangos;
	}
}
