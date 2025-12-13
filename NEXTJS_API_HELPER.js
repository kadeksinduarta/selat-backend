// API Helper untuk Next.js Frontend
// Simpan file ini di: lib/api.js atau utils/api.js

const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';

// Helper function untuk membuat request dengan authentication
async function apiRequest(endpoint, options = {}) {
  const token = localStorage.getItem('admin_token');
  
  const headers = {
    'Content-Type': 'application/json',
    ...options.headers,
  };

  if (token) {
    headers['Authorization'] = `Bearer ${token}`;
  }

  const response = await fetch(`${API_BASE_URL}${endpoint}`, {
    ...options,
    headers,
  });

  const data = await response.json();

  if (!response.ok) {
    throw new Error(data.message || 'Something went wrong');
  }

  return data;
}

// ==================== AUTH ====================

export async function login(email, password) {
  const data = await apiRequest('/admin/login', {
    method: 'POST',
    body: JSON.stringify({ email, password }),
  });
  
  // Simpan token ke localStorage
  if (data.token) {
    localStorage.setItem('admin_token', data.token);
  }
  
  return data;
}

export async function logout() {
  localStorage.removeItem('admin_token');
  // Optional: call API logout endpoint
}

// ==================== USERS ====================

export async function getUsers() {
  return apiRequest('/users');
}

export async function getUser(id) {
  return apiRequest(`/users/${id}`);
}

export async function createUser(userData) {
  return apiRequest('/users', {
    method: 'POST',
    body: JSON.stringify(userData),
  });
}

export async function updateUser(id, userData) {
  return apiRequest(`/users/${id}`, {
    method: 'PUT',
    body: JSON.stringify(userData),
  });
}

export async function deleteUser(id) {
  return apiRequest(`/users/${id}`, {
    method: 'DELETE',
  });
}

// ==================== ADMINS ====================

export async function getAdmins() {
  return apiRequest('/admins');
}

export async function getAdmin(id) {
  return apiRequest(`/admins/${id}`);
}

export async function createAdmin(adminData) {
  return apiRequest('/admins', {
    method: 'POST',
    body: JSON.stringify(adminData),
  });
}

export async function updateAdmin(id, adminData) {
  return apiRequest(`/admins/${id}`, {
    method: 'PUT',
    body: JSON.stringify(adminData),
  });
}

export async function deleteAdmin(id) {
  return apiRequest(`/admins/${id}`, {
    method: 'DELETE',
  });
}

// ==================== PRODUCTS ====================

export async function deleteProduct(id) {
  return apiRequest(`/dashboard/products/${id}`, {
    method: 'DELETE',
  });
}

// ==================== ARTICLES ====================

export async function deleteArticle(id) {
  return apiRequest(`/dashboard/articles/${id}`, {
    method: 'DELETE',
  });
}
