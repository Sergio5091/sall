<template>
  <div ref="container" class="absolute inset-0 overflow-hidden">
    <canvas ref="canvas" class="absolute inset-0 w-full h-full"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import * as THREE from 'three'

const container = ref(null)
const canvas = ref(null)
let scene, camera, renderer
let particles = []
let floatingObjects = []
let animationId
let mouseX = 0
let mouseY = 0

onMounted(() => {
  initScene()
  createParticles()
  createFloatingObjects()
  animate()
  
  // Écouter les mouvements de souris
  window.addEventListener('mousemove', onMouseMove)
  window.addEventListener('resize', onWindowResize)
})

onUnmounted(() => {
  if (animationId) {
    cancelAnimationFrame(animationId)
  }
  window.removeEventListener('mousemove', onMouseMove)
  window.removeEventListener('resize', onWindowResize)
  
  // Nettoyer Three.js
  if (renderer) {
    renderer.dispose()
  }
})

function initScene() {
  // Scene
  scene = new THREE.Scene()
  scene.fog = new THREE.FogExp2(0x121118, 0.0008)

  // Camera
  camera = new THREE.PerspectiveCamera(
    75,
    window.innerWidth / window.innerHeight,
    0.1,
    1000
  )
  camera.position.z = 50

  // Renderer
  renderer = new THREE.WebGLRenderer({
    canvas: canvas.value,
    alpha: true,
    antialias: true
  })
  renderer.setSize(window.innerWidth, window.innerHeight)
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2))
  renderer.setClearColor(0x121118, 0.8)
}

function createParticles() {
  const particleCount = 150
  const positions = new Float32Array(particleCount * 3)
  const colors = new Float32Array(particleCount * 3)
  const sizes = new Float32Array(particleCount)

  // Couleurs sobres pour particules
  const neutralColors = [
    new THREE.Color(0x808080), // Gris moyen
    new THREE.Color(0xA0A0A0), // Gris clair
    new THREE.Color(0x606060), // Gris foncé
    new THREE.Color(0x909090), // Gris
    new THREE.Color(0x707070), // Gris
    new THREE.Color(0xB0B0B0), // Gris très clair
  ]

  for (let i = 0; i < particleCount; i++) {
    // Position
    positions[i * 3] = (Math.random() - 0.5) * 100
    positions[i * 3 + 1] = (Math.random() - 0.5) * 100
    positions[i * 3 + 2] = (Math.random() - 0.5) * 100

    // Couleur
    const color = neutralColors[Math.floor(Math.random() * neutralColors.length)]
    colors[i * 3] = color.r
    colors[i * 3 + 1] = color.g
    colors[i * 3 + 2] = color.b

    // Taille
    sizes[i] = Math.random() * 3 + 1
  }

  const geometry = new THREE.BufferGeometry()
  geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3))
  geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3))
  geometry.setAttribute('size', new THREE.BufferAttribute(sizes, 1))

  const material = new THREE.ShaderMaterial({
    uniforms: {
      time: { value: 0 }
    },
    vertexShader: `
      attribute float size;
      attribute vec3 color;
      varying vec3 vColor;
      uniform float time;
      
      void main() {
        vColor = color;
        vec4 mvPosition = modelViewMatrix * vec4(position, 1.0);
        gl_PointSize = size * (300.0 / -mvPosition.z) * (1.0 + sin(time + position.x) * 0.3);
        gl_Position = projectionMatrix * mvPosition;
      }
    `,
    fragmentShader: `
      varying vec3 vColor;
      
      void main() {
        float r = distance(gl_PointCoord, vec2(0.5, 0.5));
        if (r > 0.5) discard;
        
        float alpha = 1.0 - (r * 2.0);
        gl_FragColor = vec4(vColor, alpha * 0.8);
      }
    `,
    transparent: true,
    blending: THREE.AdditiveBlending,
    depthWrite: false
  })

  const particleSystem = new THREE.Points(geometry, material)
  scene.add(particleSystem)
  particles.push(particleSystem)
}

function createFloatingObjects() {
  // Matériel de jeu vidéo réaliste - palette sobre
  const gamingHardware = [
    // Manette PlayStation/DualSense
    {
      name: 'controller',
      parts: [
        { geometry: new THREE.BoxGeometry(6, 2, 3), color: 0x2a2a2a, position: [0, 0, 0] }, // Corps principal gris foncé
        { geometry: new THREE.BoxGeometry(1.5, 0.8, 1.5), color: 0x1a1a1a, position: [2.5, 0.5, 0] }, // Stick droit noir
        { geometry: new THREE.BoxGeometry(1.5, 0.8, 1.5), color: 0x1a1a1a, position: [-2.5, 0.5, 0] }, // Stick gauche noir
        { geometry: new THREE.CylinderGeometry(0.4, 0.4, 0.3, 8), color: 0x404040, position: [1.8, -0.5, 1.2] }, // Bouton R1 gris
        { geometry: new THREE.CylinderGeometry(0.4, 0.4, 0.3, 8), color: 0x404040, position: [-1.8, -0.5, 1.2] }, // Bouton L1 gris
        { geometry: new THREE.SphereGeometry(0.3, 8, 8), color: 0x606060, position: [1.2, 0.8, 1.2] }, // Boutons gris clair
        { geometry: new THREE.SphereGeometry(0.3, 8, 8), color: 0x606060, position: [1.8, 0.8, 0.8] }, 
        { geometry: new THREE.SphereGeometry(0.3, 8, 8), color: 0x606060, position: [1.2, 0.8, 0.4] }, 
        { geometry: new THREE.SphereGeometry(0.3, 8, 8), color: 0x606060, position: [1.8, 0.8, 1.6] }, 
      ]
    },
    // Écran de jeu/monitor
    {
      name: 'monitor',
      parts: [
        { geometry: new THREE.BoxGeometry(5, 3, 0.3), color: 0x000000, position: [0, 0, 0] }, // Écran noir
        { geometry: new THREE.BoxGeometry(5.5, 3.5, 0.5), color: 0x1a1a1a, position: [0, 0, -0.2] }, // Cadre gris foncé
        { geometry: new THREE.BoxGeometry(1, 2, 0.8), color: 0x2a2a2a, position: [0, -2.5, 0.5] }, // Pied gris
        { geometry: new THREE.BoxGeometry(3, 0.2, 2), color: 0x2a2a2a, position: [0, -3.5, 0.5] }, // Base gris
      ]
    },
    // PC Gamer (tour)
    {
      name: 'pc_tower',
      parts: [
        { geometry: new THREE.BoxGeometry(3, 6, 3), color: 0x1a1a1a, position: [0, 0, 0] }, // Boîtier principal noir
        { geometry: new THREE.BoxGeometry(2.8, 0.1, 2.8), color: 0x303030, position: [0, 2.5, 1.4] }, // Grille supérieure
        { geometry: new THREE.BoxGeometry(2.8, 0.1, 2.8), color: 0x303030, position: [0, -2.5, 1.4] }, // Grille inférieure
        { geometry: new THREE.CylinderGeometry(0.2, 0.2, 0.1, 8), color: 0x505050, position: [1, 0, 1.5] }, // LED grise
        { geometry: new THREE.CylinderGeometry(0.2, 0.2, 0.1, 8), color: 0x505050, position: [1, -0.5, 1.5] }, // LED grise
        { geometry: new THREE.BoxGeometry(0.5, 0.2, 0.1), color: 0x404040, position: [0, 1.5, 1.5] }, // Bouton power gris
      ]
    },
    // Casque VR
    {
      name: 'vr_headset',
      parts: [
        { geometry: new THREE.BoxGeometry(4, 2.5, 2), color: 0x2a2a2a, position: [0, 0, 0] }, // Corps principal gris
        { geometry: new THREE.BoxGeometry(3.5, 2, 0.3), color: 0x000000, position: [0, 0, 1] }, // Écran frontal noir
        { geometry: new THREE.CylinderGeometry(1.5, 1.5, 1, 8), color: 0x1a1a1a, position: [0, -1.5, 0] }, // Support visage noir
        { geometry: new THREE.BoxGeometry(0.5, 0.5, 1), color: 0x404040, position: [2, 0, 0] }, // Capteur latéral gris
        { geometry: new THREE.BoxGeometry(0.5, 0.5, 1), color: 0x404040, position: [-2, 0, 0] }, // Capteur latéral gris
        { geometry: new THREE.CylinderGeometry(0.3, 0.3, 0.5, 8), color: 0x606060, position: [0, 1.2, 1.2] }, // LED caméra grise
      ]
    },
    // Clavier gaming mécanique
    {
      name: 'keyboard',
      parts: [
        { geometry: new THREE.BoxGeometry(8, 0.5, 3), color: 0x1a1a1a, position: [0, 0, 0] }, // Corps noir
        { geometry: new THREE.BoxGeometry(7.5, 0.2, 2.5), color: 0x2a2a2a, position: [0, 0.3, 0] }, // Zone touches gris
        { geometry: new THREE.BoxGeometry(1, 0.3, 1), color: 0x404040, position: [-3, 0.5, 0] }, // Touches WASD grises
        { geometry: new THREE.BoxGeometry(1, 0.3, 1), color: 0x404040, position: [-2, 0.5, 0] }, 
        { geometry: new THREE.BoxGeometry(1, 0.3, 1), color: 0x404040, position: [-1, 0.5, 0] }, 
        { geometry: new THREE.BoxGeometry(1, 0.3, 1), color: 0x404040, position: [0, 0.5, 0] }, 
        { geometry: new THREE.BoxGeometry(2, 0.3, 1), color: 0x505050, position: [2.5, 0.5, 0] }, // Barre espace gris clair
        { geometry: new THREE.CylinderGeometry(0.2, 0.2, 0.4, 8), color: 0x606060, position: [3.5, 0.5, 0] }, // LED grise
      ]
    },
    // Souris gaming
    {
      name: 'mouse',
      parts: [
        { geometry: new THREE.BoxGeometry(2.5, 1.5, 1), color: 0x2a2a2a, position: [0, 0, 0] }, // Corps principal gris
        { geometry: new THREE.SphereGeometry(0.8, 8, 8), color: 0x1a1a1a, position: [0, 0.5, 0] }, // Partie supérieure noire
        { geometry: new THREE.CylinderGeometry(0.1, 0.1, 0.3, 8), color: 0x404040, position: [0, 0.8, 0] }, // Roue gris
        { geometry: new THREE.CylinderGeometry(0.05, 0.05, 0.1, 8), color: 0x505050, position: [-0.8, 0.2, 0.5] }, // LED DPI gris
        { geometry: new THREE.CylinderGeometry(0.05, 0.05, 0.1, 8), color: 0x505050, position: [-0.8, 0.2, -0.5] }, // LED DPI gris
      ]
    },
    // Manette Switch/Joy-Con
    {
      name: 'switch_joycon',
      parts: [
        { geometry: new THREE.BoxGeometry(3, 1.5, 1), color: 0x404040, position: [-2, 0, 0] }, // Joy-Con gauche gris
        { geometry: new THREE.BoxGeometry(3, 1.5, 1), color: 0x505050, position: [2, 0, 0] }, // Joy-Con droit gris clair
        { geometry: new THREE.BoxGeometry(2, 0.8, 0.5), color: 0x2a2a2a, position: [0, 0, 0] }, // Centre tablette gris
        { geometry: new THREE.CylinderGeometry(0.2, 0.2, 0.3, 8), color: 0x303030, position: [-2.5, 0.3, 0.5] }, // Stick gauche gris
        { geometry: new THREE.CylinderGeometry(0.2, 0.2, 0.3, 8), color: 0x303030, position: [2.5, 0.3, 0.5] }, // Stick droit gris
        { geometry: new THREE.BoxGeometry(0.3, 0.3, 0.1), color: 0x404040, position: [-1.5, 0.3, 0.5] }, // Boutons gris
        { geometry: new THREE.BoxGeometry(0.3, 0.3, 0.1), color: 0x404040, position: [1.5, 0.3, 0.5] }, // Boutons gris
      ]
    },
    // Casque audio gaming
    {
      name: 'gaming_headset',
      parts: [
        { geometry: new THREE.BoxGeometry(3, 3, 1), color: 0x2a2a2a, position: [0, 0, 0] }, // Bandeau gris
        { geometry: new THREE.CylinderGeometry(1.2, 1.2, 0.8, 8), color: 0x1a1a1a, position: [0, -1.5, 0] }, // Oreillette noire
        { geometry: new THREE.CylinderGeometry(1.2, 1.2, 0.8, 8), color: 0x1a1a1a, position: [0, 1.5, 0] }, // Oreillette noire
        { geometry: new THREE.CylinderGeometry(0.8, 0.8, 0.3, 8), color: 0x404040, position: [0, -1.5, 0] }, // Coussinet gris
        { geometry: new THREE.CylinderGeometry(0.8, 0.8, 0.3, 8), color: 0x404040, position: [0, 1.5, 0] }, // Coussinet gris
        { geometry: new THREE.CylinderGeometry(0.1, 0.1, 1.5, 8), color: 0x505050, position: [1.5, 0, 0] }, // Micro gris
        { geometry: new THREE.SphereGeometry(0.2, 8, 8), color: 0x606060, position: [0, 0, 0.6] }, // LED grise
      ]
    },
    // Console de jeu (PS5/Xbox style)
    {
      name: 'console',
      parts: [
        { geometry: new THREE.BoxGeometry(4, 1, 2), color: 0xF0F0F0, position: [0, 0, 0] }, // Corps principal blanc
        { geometry: new THREE.BoxGeometry(3.8, 0.8, 1.8), color: 0x1a1a1a, position: [0, 0, 0] }, // Centre noir
        { geometry: new THREE.CylinderGeometry(0.3, 0.3, 0.2, 8), color: 0x404040, position: [1.5, 0.3, 0] }, // Bouton power gris
        { geometry: new THREE.CylinderGeometry(0.1, 0.1, 0.1, 8), color: 0x606060, position: [1.5, 0.5, 0] }, // LED grise
        { geometry: new THREE.BoxGeometry(0.5, 0.1, 1.5), color: 0x303030, position: [-1.5, 0, 0] }, // Grille ventilation gris
        { geometry: new THREE.BoxGeometry(0.5, 0.1, 1.5), color: 0x303030, position: [-1.5, 0, 0.8] }, // Grille ventilation gris
      ]
    },
    // Volant racing
    {
      name: 'racing_wheel',
      parts: [
        { geometry: new THREE.TorusGeometry(2, 0.3, 8, 20), color: 0x2a2a2a, position: [0, 0, 0] }, // Volant gris
        { geometry: new THREE.BoxGeometry(1, 0.5, 0.3), color: 0x1a1a1a, position: [0, 0, 0] }, // Centre noir
        { geometry: new THREE.CylinderGeometry(0.2, 0.2, 1, 8), color: 0x404040, position: [0, 0, -1] }, // Axe gris
        { geometry: new THREE.BoxGeometry(0.8, 0.3, 0.2), color: 0x505050, position: [1.5, 0, 0] }, // Bouton gris clair
        { geometry: new THREE.BoxGeometry(0.8, 0.3, 0.2), color: 0x404040, position: [-1.5, 0, 0] }, // Bouton gris
        { geometry: new THREE.BoxGeometry(0.3, 0.8, 0.2), color: 0x404040, position: [0, 1.5, 0] }, // Palette gris
        { geometry: new THREE.BoxGeometry(0.3, 0.8, 0.2), color: 0x404040, position: [0, -1.5, 0] }, // Palette gris
      ]
    },
    // Joystick arcade
    {
      name: 'arcade_stick',
      parts: [
        { geometry: new THREE.BoxGeometry(6, 4, 2), color: 0x1a1a1a, position: [0, 0, 0] }, // Boîtier noir
        { geometry: new THREE.BoxGeometry(5.5, 3.5, 0.1), color: 0x2a2a2a, position: [0, 0, 1] }, // Surface gris
        { geometry: new THREE.CylinderGeometry(0.8, 0.8, 1.5, 8), color: 0x404040, position: [-1.5, 0.5, 1.5] }, // Stick gris
        { geometry: new THREE.SphereGeometry(0.5, 8, 8), color: 0x505050, position: [-1.5, 1.5, 1.5] }, // Boule stick gris clair
        { geometry: new THREE.CylinderGeometry(0.3, 0.3, 0.2, 8), color: 0x404040, position: [1.5, 0.5, 1.2] }, // Boutons gris
        { geometry: new THREE.CylinderGeometry(0.3, 0.3, 0.2, 8), color: 0x404040, position: [2, 0.5, 1.2] }, 
        { geometry: new THREE.CylinderGeometry(0.3, 0.3, 0.2, 8), color: 0x404040, position: [1.5, 0, 1.2] }, 
        { geometry: new THREE.CylinderGeometry(0.3, 0.3, 0.2, 8), color: 0x404040, position: [2, 0, 1.2] }, 
        { geometry: new THREE.CylinderGeometry(0.3, 0.3, 0.2, 8), color: 0x404040, position: [1.5, -0.5, 1.2] }, 
        { geometry: new THREE.CylinderGeometry(0.3, 0.3, 0.2, 8), color: 0x404040, position: [2, -0.5, 1.2] }, 
      ]
    },
    // Microphone streaming
    {
      name: 'microphone',
      parts: [
        { geometry: new THREE.CylinderGeometry(0.8, 0.8, 4, 8), color: 0x2a2a2a, position: [0, 0, 0] }, // Corps principal gris
        { geometry: new THREE.CylinderGeometry(0.9, 0.9, 0.3, 8), color: 0x404040, position: [0, 2, 0] }, // Grille supérieure gris
        { geometry: new THREE.CylinderGeometry(0.9, 0.9, 0.3, 8), color: 0x404040, position: [0, -2, 0] }, // Grille inférieure gris
        { geometry: new THREE.CylinderGeometry(0.1, 0.1, 2, 8), color: 0x505050, position: [0, 0, 0.9] }, // Support interne gris clair
        { geometry: new THREE.BoxGeometry(0.5, 0.2, 1), color: 0x1a1a1a, position: [0, -2.5, 0] }, // Base noir
        { geometry: new THREE.SphereGeometry(0.15, 8, 8), color: 0x606060, position: [0, 2.2, 0] }, // LED grise
      ]
    }
  ]

  for (let i = 0; i < gamingHardware.length; i++) {
    const hardware = gamingHardware[i]
    const group = new THREE.Group()
    
    // Créer chaque partie du matériel
    hardware.parts.forEach(part => {
      const material = new THREE.MeshPhongMaterial({
        color: part.color,
        emissive: part.color,
        emissiveIntensity: 0.2,
        shininess: 100,
        opacity: 0.9,
        transparent: true,
        specular: 0xffffff
      })
      
      const mesh = new THREE.Mesh(part.geometry, material)
      mesh.position.set(...part.position)
      group.add(mesh)
    })
    
    // Position spatiale 3D
    const angle = (i / gamingHardware.length) * Math.PI * 2
    const radius = 30 + Math.random() * 20
    const height = (Math.random() - 0.5) * 40
    
    group.position.set(
      Math.cos(angle) * radius,
      height,
      Math.sin(angle) * radius
    )

    // Rotation initiale
    group.rotation.set(
      Math.random() * Math.PI * 2,
      Math.random() * Math.PI * 2,
      Math.random() * Math.PI * 2
    )

    // Propriétés d'animation selon le type de matériel
    group.userData = {
      type: hardware.name,
      rotationSpeed: {
        x: (Math.random() - 0.5) * 0.015,
        y: (Math.random() - 0.5) * 0.02,
        z: (Math.random() - 0.5) * 0.01
      },
      floatSpeed: Math.random() * 0.6 + 0.3,
      floatAmplitude: Math.random() * 3 + 1.5,
      initialY: group.position.y,
      pulsePhase: Math.random() * Math.PI * 2,
      orbitSpeed: Math.random() * 0.0008 + 0.0003,
      orbitRadius: radius,
      orbitAngle: angle
    }

    scene.add(group)
    floatingObjects.push(group)
  }

  // Éclairage gaming sobre
  const ambientLight = new THREE.AmbientLight(0x1a1a2e, 0.5)
  scene.add(ambientLight)

  // Lumières douces et sobres
  const softLights = [
    { color: 0xFFFFFF, position: [20, 20, 20], intensity: 0.8 }, // Lumière blanche douce
    { color: 0xF0F0F0, position: [-20, -20, 20], intensity: 0.6 }, // Lumière grise claire
    { color: 0xE0E0E0, position: [0, 25, -20], intensity: 0.5 }, // Lumière très douce
  ]

  softLights.forEach(lightConfig => {
    const light = new THREE.PointLight(
      lightConfig.color, 
      lightConfig.intensity, 
      50
    )
    light.position.set(...lightConfig.position)
    scene.add(light)
  })

  // Lumière directionnelle principale
  const directionalLight = new THREE.DirectionalLight(0xFFFFFF, 0.7)
  directionalLight.position.set(5, 10, 5)
  scene.add(directionalLight)
}

function animate() {
  animationId = requestAnimationFrame(animate)

  const time = Date.now() * 0.001

  // Animer les particules
  particles.forEach(particleSystem => {
    if (particleSystem.material.uniforms) {
      particleSystem.material.uniforms.time.value = time
    }

    // Rotation lente du système de particules
    particleSystem.rotation.y = time * 0.05
    particleSystem.rotation.x = time * 0.02
  })

  // Animer les objets gaming avec mouvements réalistes
  floatingObjects.forEach((obj, index) => {
    // Animation spécifique selon le type de matériel
    switch(obj.userData.type) {
      case 'controller':
        // La manette flotte doucement comme si elle était tenue
        obj.rotation.y += 0.008
        obj.rotation.z = Math.sin(time * 0.4) * 0.1
        obj.rotation.x = Math.cos(time * 0.3) * 0.05
        break
      case 'monitor':
        // L'écran balance légèrement comme un moniteur sur bureau
        obj.rotation.x += 0.003
        obj.rotation.y = Math.sin(time * 0.2) * 0.1
        break
      case 'pc_tower':
        // Le PC tourne lentement pour montrer les LEDs
        obj.rotation.y += 0.005
        obj.rotation.z = Math.sin(time * 0.6) * 0.05
        break
      case 'vr_headset':
        // Le casque VR balance comme s'il était porté
        obj.rotation.x = Math.sin(time * 0.5) * 0.15
        obj.rotation.y += 0.01
        obj.rotation.z = Math.cos(time * 0.4) * 0.1
        break
      case 'keyboard':
        // Le clavier reste relativement stable avec petite vibration
        obj.rotation.y = Math.sin(time * 2) * 0.02
        obj.rotation.x = Math.cos(time * 1.5) * 0.01
        break
      case 'mouse':
        // La souris a un mouvement de clic subtil
        obj.rotation.x = Math.sin(time * 3) * 0.08
        obj.rotation.y += 0.006
        break
      case 'switch_joycon':
        // Les Joy-Con détachés bougent indépendamment
        obj.rotation.y += 0.012
        obj.rotation.x = Math.sin(time * 0.8) * 0.2
        break
      case 'gaming_headset':
        // Le casque audio balance doucement
        obj.rotation.y += 0.007
        obj.rotation.z = Math.sin(time * 0.4) * 0.12
        break
      case 'console':
        // La console reste stable avec petite vibration
        obj.rotation.y += 0.004
        obj.rotation.x = Math.sin(time * 1.2) * 0.03
        break
      case 'racing_wheel':
        // Le volant tourne comme en jeu
        obj.rotation.z += 0.015
        obj.rotation.y = Math.sin(time * 0.5) * 0.1
        break
      case 'arcade_stick':
        // Le joystick arcade a des mouvements de jeu rapides
        obj.rotation.x = Math.sin(time * 2) * 0.1
        obj.rotation.y += 0.009
        break
      case 'microphone':
        // Le microphone balance comme s'il était utilisé
        obj.rotation.x = Math.sin(time * 0.3) * 0.08
        obj.rotation.y += 0.005
        break
      default:
        // Animation par défaut
        obj.rotation.x += obj.userData.rotationSpeed.x
        obj.rotation.y += obj.userData.rotationSpeed.y
        obj.rotation.z += obj.userData.rotationSpeed.z
    }

    // Mouvement orbital autour du centre (comme des équipements en showroom)
    obj.userData.orbitAngle += obj.userData.orbitSpeed
    const orbitX = Math.cos(obj.userData.orbitAngle) * obj.userData.orbitRadius
    const orbitZ = Math.sin(obj.userData.orbitAngle) * obj.userData.orbitRadius
    
    // Flottaison verticale selon le poids du matériel
    let floatMultiplier = 1
    if (obj.userData.type === 'pc_tower' || obj.userData.type === 'monitor') {
      floatMultiplier = 0.5 // Matériel lourd flotte moins
    } else if (obj.userData.type === 'controller' || obj.userData.type === 'mouse') {
      floatMultiplier = 1.3 // Matériel léger flotte plus
    } else if (obj.userData.type === 'vr_headset' || obj.userData.type === 'gaming_headset') {
      floatMultiplier = 0.8 // Équipement porté
    }
    
    obj.position.x = orbitX + (mouseX * 0.015 - orbitX * 0.008)
    obj.position.y = obj.userData.initialY + 
      Math.sin(time * obj.userData.floatSpeed + obj.userData.pulsePhase) * 
      obj.userData.floatAmplitude * floatMultiplier
    obj.position.z = orbitZ + (mouseY * 0.015 - orbitZ * 0.008)
  })

  // Mouvement de caméra style showroom gaming
  camera.position.x += (mouseX * 0.3 - camera.position.x) * 0.05
  camera.position.y += (-mouseY * 0.3 - camera.position.y) * 0.05
  
  // Effet de zoom doux comme si on examinait les équipements
  const zoomEffect = Math.sin(time * 0.25) * 2 + 50
  camera.position.z += (zoomEffect - camera.position.z) * 0.015
  
  camera.lookAt(scene.position)

  renderer.render(scene, camera)
}

function onMouseMove(event) {
  mouseX = (event.clientX - window.innerWidth / 2) / window.innerWidth * 2
  mouseY = (event.clientY - window.innerHeight / 2) / window.innerHeight * 2
}

function onWindowResize() {
  if (!camera || !renderer) return

  camera.aspect = window.innerWidth / window.innerHeight
  camera.updateProjectionMatrix()
  renderer.setSize(window.innerWidth, window.innerHeight)
}
</script>

<style scoped>
canvas {
  display: block;
}
</style>
