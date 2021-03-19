
//import { FaceNormalsHelper } from 'https://threejs.org/three/examples/jsm/helpers/FaceNormalsHelper.js';

var scene = new THREE.Scene();
//var camera = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 0.1, 1000 ); // based
//var camera = new THREE.PerspectiveCamera( 75, 10, 0.1, 1000 );
//var camera = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 0.1, 1000 );
var camera;
camera = new THREE.PerspectiveCamera( 75, 800 / 800, 0.1, 25000 ); // 1000
camera.position.z = 30;
//    camera = new THREE.PerspectiveCamera(40, window.innerWidth / window.innerHeight, 0.2, 25000);
//    camera.position.set(100, -400, 2000);
//camera.position.x = 15;
//        scene.add(camera);

var renderer = new THREE.WebGLRenderer();
var elem = document.querySelector('#scene');

/* ===== ===== ===== ===== ===== */


			var lights = [];
			lights[ 0 ] = new THREE.PointLight( 0x222222, 1, 0 );
			lights[ 0 ] = new THREE.PointLight( 0xffffff, 1, 0 );
			lights[ 1 ] = new THREE.PointLight( 0xff0000, 1, 0 );
			lights[ 2 ] = new THREE.PointLight( 0x0000ff, 1, 0 );

			lights[ 0 ].position.set( 0, 200, 200 );
			lights[ 0 ].position.set( 0, 200, 100 );
			lights[ 1 ].position.set( -100, -200, 20 );
			lights[ 2 ].position.set( 100, - 200, 20 );

			scene.add( lights[ 0 ] );
			scene.add( lights[ 1 ] );
			scene.add( lights[ 2 ] );

/* ===== ===== ===== ===== ===== */

			var text = 
"analizysl.com\n\
  warehouse",

				height = 20,
				size = 70,
				hover = -10,
				textY = -14,
				hover = textY,

				curveSegments = 4,

				bevelThickness = 2,
				bevelSize = 1.5,
				bevelEnabled = true,

				font = undefined,

				fontName = "optimer", // helvetiker, optimer, gentilis, droid sans, droid serif
				fontWeight = "bold"; // normal bold

/* ===== ===== ===== ===== ===== */

scene.background = new THREE.Color( 0x000000 );
scene.background = new THREE.Color( 0xffffff );
//scene.background = new THREE.Color( 0x00ffff );
//scene.fog = new THREE.Fog( 0x000000, 250, 1400 );

//			renderer.setSize( window.innerWidth, window.innerHeight );
//			renderer.setSize( elem.clientWidth, elem.clientWidth );
			renderer.setSize( 1000, 1000 );
			console.table([ elem.clientWidth, elem.clientWidth/2 ]);
//			renderer.setSize( 400, 300 );
            
//			document.body.appendChild( renderer.domElement );
//			document.querySelector('#scene').appendChild( renderer.domElement );
			elem.appendChild( renderer.domElement );

/* ===== ===== ===== ===== ===== */

var data = {
    width: 15,
    height: 15,
    depth: 15,
    widthSegments: 1,
    heightSegments: 1,
    depthSegments: 1
};
var geometry = new THREE.BoxGeometry();
//var geometry = new THREE.BoxGeometry(data.width, data.height, data.depth, data.widthSegments, data.heightSegments, data.depthSegments);
var material = new THREE.MeshBasicMaterial( { 
//    wireframe: true,
    color: 0x00ff00,
//    flatShading: true,
    t:1000
} );
//geometry.computeVertexNormals();
    materials = [
        new THREE.MeshPhongMaterial( { color: 0xffffff, flatShading: false } ), // front
        new THREE.MeshPhongMaterial( { color: 0xffffff, flatShading: false } ), // front
        new THREE.MeshPhongMaterial( { color: 0xffffff, flatShading: false } ), // front
        new THREE.MeshPhongMaterial( { color: 0xffffff, flatShading: false } ), // front
        new THREE.MeshPhongMaterial( { color: 0xffffff, flatShading: false } ), // front
        new THREE.MeshPhongMaterial( { color: 0xffffff } ) // side
    ];
//var cube = new THREE.Mesh( geometry, material );
var cube = new THREE.Mesh( geometry, materials );
cube.castShadow = true; //default is false
cube.receiveShadow = false; //default
scene.add( cube );

//helper = new FaceNormalsHelper( cube, 2, 0x00ff00, 1 );
//scene.add( helper );

/* ===== ===== ===== ===== ===== */

var box = new THREE.SphereGeometry(2, 10, -30);
var mesh = new THREE.MeshBasicMaterial({
    wireframe: true,
//    color: 0xffffff
    color: 0x000000
});
var cube2 = new THREE.Mesh(box, mesh);
    cube2.position.x = 0;
    cube2.position.y = 1;
    cube2.position.z = 25;
    cube.position.y = 1;
    cube.position.z = 25;
scene.add(cube2);

/* ===== ===== ===== ===== ===== */

//https://raw.githubusercontent.com/PavelLaptev/test-rep/master/threejs-post/diamond.json
//https://medium.com/@PavelLaptev/three-js-for-beginers-32ce451aabda

var diamondsGroup = false;
var distance = 400;
var distance = 0;
function createDiamond() {
  //create a group container
    diamondsGroup = new THREE.Object3D();
  //setting up loader for a model
//    var loader = new THREE.JSONLoader(); // three.js:50750 THREE.JSONLoader has been removed.
    var loader = new THREE.ObjectLoader();
  //load model and clone it 
var dmap = threejs_fonts['diamond'];
    console.log({' dmap':dmap});
 loader.load('https://raw.githubusercontent.com/PavelLaptev/test-rep/master/threejs-post/diamond.json', function(geometry) {
// loader.load(dmap, function(geometry) {
    console.table({'loader.load dmap':dmap});
    console.table(geometry);
//        for (var i = 0; i < 1; i++) { // 60
//            var material = new THREE.MeshPhongMaterial({
////                color: Math.random() * 0xff00000 - 0xff00000,
//                color: 0xff00000,
//                shading: THREE.FlatShading
//            });
//            var diamond = new THREE.Mesh(geometry, material);
//            diamond.position.x = Math.random() * -distance * 6;
//            diamond.position.y = Math.random() * -distance * 2;
//            diamond.position.z = Math.random() * distance * 3;
//            diamond.rotation.y = Math.random() * 2 * Math.PI;
////            diamond.scale.x = diamond.scale.y = diamond.scale.z = Math.random() * 50 + 10;
//            diamond.scale.x = diamond.scale.y = diamond.scale.z = Math.random() * 50 + 10;
//            diamondsGroup.add(diamond);
//        }
//
////        diamondsGroup.position.x = 1400;
////        diamondsGroup.position.x = 1400;
////        diamondsGroup.position.z = -1400;
////        diamondsGroup.position.z = 0;
//        scene.add(diamondsGroup);
            var material = new THREE.MeshPhongMaterial({
//                color: Math.random() * 0xff00000 - 0xff00000,
                color: 0xff00000,
                shading: THREE.FlatShading
            });
            var diamond = new THREE.Mesh(geometry, material);
            scene.add(diamond);
        
        //we will delete this line later
//        renderer.render(scene, camera);
    },

	// onProgress callback
	function ( xhr ) {
		console.log( (xhr.loaded / xhr.total * 100) + '% loaded' );
	},

	// onError callback
	function ( err ) {
		console.error( 'An error happened' );
	});
};
//createDiamond();

/* ===== ===== ===== ===== ===== */
if(10){

// helvetiker_regular.typeface.json
// gentilis_bold.typeface.json
// droid_serif_regular.typeface.json
// droid_sans_regular.typeface.json
console.table(threejs_fonts);
var loader = new THREE.FontLoader();

var textMesh1=false,geometry ,
    textGeo,tfont;
tfont = threejs_fonts['droid_sans__normal__normal'];
tfont = threejs_fonts['helvetiker__normal__normal'];
loader.load( tfont, function ( font ) { // 'fonts/helvetiker_regular.typeface.json'
    console.table({'loader.load':tfont});

    textGeo = new THREE.TextGeometry( text, {
//    wireframe: true,
		font: font,
////		size: 1, // 80,
//		size: 5,
//		height: 5,
//		curveSegments: 12,
////		curveSegments: 1,
//		bevelEnabled: true,
//		bevelThickness: 10,
//		bevelSize: 8,
//		bevelOffset: 0,
//		bevelSegments: 5
        
        
						size: 5,
						height: 2,
						curveSegments: 12,
//						font: "helvetiker",
//						weight: "regular",
						bevelEnabled: false,
						bevelThickness: 1,
						bevelSize: 0.5,
						bevelOffset: 0.0,
						bevelSegments: 3
	} );
    
    textGeo.computeBoundingBox();
//    textGeo.computeVertexNormals();
    textGeo.center();

    materials = [
        new THREE.MeshPhongMaterial( { color: 0xffffff, flatShading: false } ), // front
        new THREE.MeshPhongMaterial( { color: 0xffffff } ) // side
    ];
//    materials = [
//        new THREE.MeshPhongMaterial( { color: 0xffffff,wireframe: true, flatShading: false } ), // front
//        new THREE.MeshPhongMaterial( { color: 0xffffff,wireframe: true } ) // side
//    ];
    
    var centerOffset = - 0.5 * ( textGeo.boundingBox.max.x - textGeo.boundingBox.min.x );
    textGeo = new THREE.BufferGeometry().fromGeometry( textGeo );
    textMesh1 = new THREE.Mesh( textGeo, materials );
//
    textMesh1.position.x = centerOffset;
    textMesh1.position.y = hover;
    textMesh1.position.z = 0;
//
    textMesh1.position.x = 0;
//    textMesh1.position.y = 0;
    textMesh1.position.z = -10;
//
//    textMesh1.position.x = 0;
//    textMesh1.position.y = 0;
//    textMesh1.position.z = 0;
//
//    textMesh1.rotation.x = 0;
//    textMesh1.rotation.y = Math.PI * 2;

    //				group.add( textMesh1 );
    scene.add( textMesh1 );
//    scene.add( textGeo );
    
} );
}

/* ===== ===== ===== ===== ===== */

//			camera.position.z = 5;

var animate = function () {
    requestAnimationFrame( animate );

    if(textMesh1){
//        textMesh1.rotation.x += 0.1;
        textMesh1.rotation.y += 0.03;
    }

    cube.rotation.x += 0.01;
    cube.rotation.y += 0.01;
    cube.rotation.z += 0.01;
                
    cube2.rotation.y += 0.01;
    cube2.rotation.x += 0.01;
    cube2.rotation.z += 0.01;

    renderer.render( scene, camera );
};

/* ===== ===== ===== ===== ===== */

			animate();

/* ===== ===== ===== ===== ===== */
/* ===== ===== ===== ===== ===== */

            
            
////I used threejs.org to help me, since this is my first time using three.js
//var scene = new THREE.Scene();
//var cam = new THREE.PerspectiveCamera(100, window.innerWidth/window.innerHeight, 0.1, 1000);
//var renderer = new THREE.WebGLRenderer();
//renderer.setSize(window.innerWidth, window.innerHeight);
//document.body.appendChild( renderer.domElement );
//
//var box = new THREE.SphereGeometry(2, 10, -30);
//var mesh = new THREE.MeshBasicMaterial({
//wireframe: true,
//  color: 0xffffff
//});
//var cube = new THREE.Mesh(box, mesh);
//scene.add(cube);
//cam.position.z = 3;
//var render = function () {
//				requestAnimationFrame( render );
//  cube.rotation.y += 0.01;
//  cube.rotation.x += 0.01;
//  cube.rotation.z +=0.01;
//  renderer.render(scene, cam);
//			};
//
//			render();
