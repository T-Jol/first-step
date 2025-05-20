<template>
  <canvas ref="canvas" class="star-bg"></canvas>
</template>

<script>
export default {
  name: 'StarBackground',
  mounted() {
    this.initStars();
    window.addEventListener('resize', this.resizeCanvas);
  },
  beforeUnmount() {
    window.removeEventListener('resize', this.resizeCanvas);
    cancelAnimationFrame(this.rafId);
  },
  methods: {
    initStars() {
      this.canvas = this.$refs.canvas;
      this.ctx = this.canvas.getContext('2d');
      this.resizeCanvas();
      this.createStars();
      this.animate();
    },
    resizeCanvas() {
      if (!this.canvas) return;
      this.canvas.width = window.innerWidth;
      this.canvas.height = window.innerHeight;
      this.createStars();
    },
    createStars() {
      const w = this.canvas.width;
      const h = this.canvas.height;
      const density = Math.floor((w * h) / 2500); // nombre d'étoiles selon la taille
      this.stars = Array.from({ length: density }, () => ({
        x: Math.random() * w,
        y: Math.random() * h,
        r: Math.random() * 1.1 + 0.2,
        speed: Math.random() * 0.15 + 0.05,
        alpha: Math.random() * 0.5 + 0.5
      }));
    },
    animate() {
      const ctx = this.ctx;
      const w = this.canvas.width;
      const h = this.canvas.height;
      ctx.clearRect(0, 0, w, h);
      for (const star of this.stars) {
        ctx.save();
        ctx.globalAlpha = star.alpha;
        ctx.beginPath();
        ctx.arc(star.x, star.y, star.r, 0, 2 * Math.PI);
        ctx.fillStyle = '#a3f0ff';
        ctx.shadowColor = '#7fd6ff';
        ctx.shadowBlur = 8;
        ctx.fill();
        ctx.restore();
        // Mouvement lent vers le bas
        star.y += star.speed;
        if (star.y > h) {
          star.y = 0;
          star.x = Math.random() * w;
        }
      }
      this.rafId = requestAnimationFrame(this.animate);
    }
  }
};
</script>

<style scoped>
.star-bg {
  position: fixed;
  top: 0; left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 0;
  pointer-events: none;
  background: transparent;
}
</style> 