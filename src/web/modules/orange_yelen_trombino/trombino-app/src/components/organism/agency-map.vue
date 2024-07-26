<script setup lang="ts">
import {onMounted, ref} from "vue";
import { map, latLng, tileLayer, MapOptions, Icon, marker } from "leaflet";
type D = Icon.Default & {
  _getIconUrl?: string;
};
delete (Icon.Default.prototype as D)._getIconUrl;
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png'
import markerIcon from 'leaflet/dist/images/marker-icon.png'
import markerShadow from 'leaflet/dist/images/marker-shadow.png'

Icon.Default.mergeOptions({
  iconRetinaUrl: markerIcon2x,
  iconUrl: markerIcon,
  shadowUrl: markerShadow,
})

const orangeAgencyMap = ref<HTMLDivElement | null>(null)
const options: MapOptions = {
  center: latLng(5.343924, -4.0645722),
  zoom: 12,
  zoomControl: true,
  zoomAnimation: true,
  layers: [],
};

const initMap = (mapElementRef: HTMLDivElement) => {
  const orangeAgencyMapInstance = map(mapElementRef, options)
  const agencyMapTileLayer = tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    minZoom: 6,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  })
  agencyMapTileLayer.addTo(orangeAgencyMapInstance);

  marker([5.3332586, -4.0572755]).addTo(orangeAgencyMapInstance)
    .bindPopup('A pretty CSS3 popup.<br> Easily customizable.')
    .openPopup()

  orangeAgencyMapInstance.on('zoomstart', (e) => {
    console.log('ZOOM STARTED', e.target);
  });
  orangeAgencyMapInstance.on('resize', (e) => {
    console.log('MAP resize', e);
  });
  orangeAgencyMapInstance.on('load', (e) => {
    console.log('MAP LOAD', e);
  });
  orangeAgencyMapInstance.on('zoomend', (e) => {
    console.log('ZOOM END', e.target);
  });
  orangeAgencyMapInstance.on('movestart', (e) => {
    console.log('MAP MOVE START', e.target);
  });
  orangeAgencyMapInstance.on('moveend', (e) => {
    console.log('MAP MOVE END', e.target);
  });
  orangeAgencyMapInstance.on('zoomlevelschange', (e) => {
    console.log('ZOOM LEVEL CHANGE', e);
  });
}
onMounted(() => {
  if(orangeAgencyMap.value) initMap(orangeAgencyMap.value);

})
</script>
<template>
    <div id="orangeAgencyMap" ref="orangeAgencyMap"></div>
</template>
<style lang="scss" scoped>
#orangeAgencyMap {
  min-height: 600px;
  width: 100%;
  overflow: hidden;
  height: 100%;
}
</style>
