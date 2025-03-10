<template>
  <div class="container">
    <!-- Header -->
    <header>
      <h1>RVT Dienesta viesnīcas ieraksti</h1>
    </header>

    <!-- Main Content -->
    <main>
      <button @click="showCheckInPopup" class="btn">Check In</button>
      <button @click="showCheckOutPopup" class="btn">Check Out</button>
    </main>

    <!-- Footer -->
    <footer>
      <p>Contact: example@example.com</p>
      <button @click="adminLogin" class="admin-login-button">
        <img src="https://img.icons8.com/ios-filled/50/000000/admin-settings-male.png" alt="Admin Login" />
      </button>
    </footer>

    <!-- Check In/Out Popup -->
    <div v-if="showPopup" class="popup">
      <div class="popup-content">
        <h2>{{ isCheckIn ? 'Check In' : 'Check Out' }}</h2>
        <form @submit.prevent="isCheckIn ? checkInStudent() : checkOutStudent()">
          <div>
            <label for="floor">Floor:</label>
            <select id="floor" v-model="selectedFloor" @change="updateRooms" required>
              <option value="" disabled>Select Floor</option>
              <option v-for="floor in floors" :key="floor" :value="floor">{{ floor }}</option>
            </select>
          </div>
          <div>
            <label for="room">Room:</label>
            <select id="room" v-model="selectedRoom" @change="fetchStudents" required>
              <option value="" disabled>Select Room</option>
              <option v-for="room in rooms" :key="room" :value="room">{{ room }}</option>
            </select>
          </div>
          <div v-if="students.length > 0">
            <label for="student">Student:</label>
            <select id="student" v-model="selectedStudent" required>
              <option value="" disabled>Select Student</option>
              <option v-for="student in students" :key="student.id" :value="student.id">{{ student.name }} {{ student.surname }}</option>
            </select>
          </div>
          <button type="submit" class="btn">{{ isCheckIn ? 'Check In' : 'Check Out' }}</button>
          <button @click="closePopup" class="btn">Cancel</button>
        </form>
      </div>
    </div>

    <!-- Notification -->
    <div v-if="notification.message" :class="['notification', notification.type]">
      {{ notification.message }}
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'MainPage',
  data() {
    return {
      showPopup: false,
      isCheckIn: true,
      selectedFloor: '',
      selectedRoom: '',
      selectedStudent: '',
      floors: [1, 2, 3, 4, 5],
      rooms: [],
      students: [],
      notification: {
        message: '',
        type: ''
      }
    }
  },
  methods: {
    showCheckInPopup() {
      this.isCheckIn = true;
      this.resetForm();
      this.showPopup = true;
    },
    showCheckOutPopup() {
      this.isCheckIn = false;
      this.resetForm();
      this.showPopup = true;
    },
    closePopup() {
      this.showPopup = false;
    },
    resetForm() {
      this.selectedFloor = '';
      this.selectedRoom = '';
      this.selectedStudent = '';
      this.rooms = [];
      this.students = [];
    },
    async updateRooms() {
      try {
        const response = await axios.get(`/api/rooms?floor=${this.selectedFloor}`);
        this.rooms = response.data;
      } catch (error) {
        console.error('Error fetching rooms:', error);
      }
    },
    async fetchStudents() {
      if (this.selectedRoom) {
        try {
          const response = await axios.get(`/api/students?room=${this.selectedRoom}`);
          this.students = response.data;
        } catch (error) {
          console.error('Error fetching students:', error);
        }
      }
    },
    async checkInStudent() {
      try {
        const response = await axios.put(`/api/students/${this.selectedStudent}`, { checkedIn: true });
        this.showNotification('Student checked in successfully', 'success');
        this.closePopup();
      } catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
          this.showNotification('Error checking in student: ' + Object.values(error.response.data.errors).join(', '), 'error');
        } else {
          this.showNotification('Error checking in student', 'error');
        }
        console.error('Error checking in student:', error);
      }
    },
    async checkOutStudent() {
      try {
        const response = await axios.put(`/api/students/${this.selectedStudent}`, { checkedIn: false });
        this.showNotification('Student checked out successfully', 'success');
        this.closePopup();
      } catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
          this.showNotification('Error checking out student: ' + Object.values(error.response.data.errors).join(', '), 'error');
        } else {
          this.showNotification('Error checking out student', 'error');
        }
        console.error('Error checking out student:', error);
      }
    },
    showNotification(message, type) {
      this.notification.message = message;
      this.notification.type = type;
      setTimeout(() => {
        this.notification.message = '';
        this.notification.type = '';
      }, 3000);
    },
    adminLogin() {
      window.location.href = '/admin-login';
    }
  }
}
</script>

<style scoped src="../../css/main-page.css"></style>
