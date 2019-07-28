@extends('layouts.app')

@section('content')
    <div class="container" >
            
            <div id="map">
                                    
            </div>


            <div class="columns">
                <div class="column is-2 is-offset-6">
                    <button class="button is-small is-link"> Delete</button>
                </div> 
                <div class="column is-2 ">
                    <button class="button is-small is-warning"> Edit</button>
                </div>
                <div class="column is-2 ">
                    <button class="button is-small is-success" onclick="savePolygon()"> add</button>
                </div>

            </div>

            <div v-if="isActive" class="modal is-active">
                <div class="modal-background" @click="hideModal"></div>
                <div class="modal-content">
                    <div class="list is-clipped">
                        <div class="title is-5">
                            Polygons
                        </div>

                        @foreach($polygons as $poly)
                            <a class="list-item">
                                {{$poly->name}}
                            </a>
                        @endforeach  
                    </div>
                </div>
                <button class="modal-close is-large" aria-label="close" @click="hideModal"></button>
            </div>
            
            <button class="button is-normal is-dark" @click="showModal" > List Polygons </button>
            
        </div> 

    </div>

    <!-- Scripts -->
    
    

    <style>
        /* Always set the map height explicitly to define the size of the div
            * element that contains the map. */
        #map {
            height: 400px;
            width: 800px;
        }
        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
@endsection

@section('js-file')
<script type="text/javascript">
    var map;
    var drawingManager;
    var drawRequest  = '<?php echo $polygons; ?>';
    

    var polyJSON = null;
    function savePolygon(){
        if(polyJSON == null){
            alert("First crate a polygon");
        }

        $.ajax({
            url: '/show',
            type: 'POST',
            data: polyJSON,
            success: function(data) {
                alert(polyJSON);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                // alert(polyJSON); 
            } 
        
        });
    };

    //Draw polyons from the request return string
    function drawPolygons(x){

        var jsonObj = JSON.parse(x);

        for( i=0; i< jsonObj.length; i++){

                var bermudaTriangle = new google.maps.Polygon(
                {
                    paths: JSON.parse(jsonObj[i].polyString),
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 3,
                    fillColor: '#FF0000',
                    fillOpacity: 0.35
                }
            );
            bermudaTriangle.setMap(map);
        }

    };
    function initMap() {
        drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.MARKER,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: ['polygon', 'marker',  'rectangle']
                },
                markerOptions: {icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'},

        });
    };

    document.addEventListener('DOMContentLoaded', function () {        

        map = new google.maps.Map(document.getElementById('map'), {
                center: {lat:  -25.7479, lng: 28.2293},
                zoom: 5,
                streetViewControl : false
        });

        drawingManager.setMap(map);
        console.log("domLoaded")
        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
            var path = {};
            path.poly = JSON.stringify(event.overlay.getPath().getArray());
            path.name = "First";
            polyJSON = JSON.stringify(path);
            // alert(polyJSON);
        });

        drawPolygons(drawRequest);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    })

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCXM2zxbvNqBq_zE2yGfxj8nLmwBvF9Bqs&libraries=drawing&callback=initMap"
async defer>
</script>


@endsection