<template>
  <div class="container">
    <!-- Header -->
    <header>
      <h1>Admin Login</h1>
    </header>

    <!-- Main Content -->
    <main>
      <form @submit.prevent="login">
        <div>
          <label for="username">Username:</label>
          <input type="text" id="username" v-model="username" required>
        </div>
        <div>
          <label for="password">Password:</label>
          <input type="password" id="password" v-model="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
      </form>
    </main>

    <!-- Footer -->
    <footer>
      <p>Contact: admin@example.com</p>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'AdminLogin',
  data() {
    return {
      username: '',
      password: ''
    }
  },
  methods: {
    login() {
      // Logic for admin login
      if (this.username === 'admin' && this.password === 'admin1234') {
        window.location.href = '/admin-dashboard';
      } else {
        alert('Invalid credentials');
      }
    }
  }
}
</script>

<style scoped src="../../css/admin-login.css"></style>
