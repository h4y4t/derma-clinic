# 🧴 Derma Clinic – AI-Powered Dermatology Web Platform

Derma Clinic is a full-stack web application designed to assist in **skin disease detection and dermatology clinic management** using **Artificial Intelligence**.  
The platform combines a modern web interface with a deep learning model to provide preliminary skin condition analysis and appointment management.

> ⚠️ This system is intended for educational and supportive purposes only and does **not** replace professional medical diagnosis.

---

## 🚀 Features

### 👤 User Side
- User registration & authentication
- Patient profile creation
- Upload skin images for AI-based analysis
- View AI prediction results
- Book dermatology appointments

### 🧑‍⚕️ Doctor Side
- Doctor authentication
- View and manage appointments
- Access patient medical profiles (read-only)
- Add diagnoses and medical notes after appointments

### 🤖 AI Skin Disease Detection
- Image-based skin disease classification
- Uses a **pre-trained deep learning model**
- Fast inference via **Flask API**
- PHP ↔ Flask communication using **cURL**

---

## 🧠 AI Model
The system integrates a **pre-trained vision model** fine-tuned for skin disease classification:
- Model hosted on **Hugging Face**
- Loaded once and kept in memory using Flask for performance
- Returns prediction results to the web application

---

## 🛠 Tech Stack

### Frontend
- HTML5
- CSS3
- JavaScript

### Backend
- PHP
- Flask (Python)
- cURL for API communication

### Database
- MySQL

### AI / ML
- Python
- PyTorch
- Pre-trained CNN / Vision Transformer model

---

## 🗂 Database Structure (Simplified)

- `users` – registered users
- `doctors` – doctor accounts
- `appointments` – booking and status
- `patient_profiles` – medical data
- `diagnoses` – doctor notes and results

---

## 🔄 System Architecture

1. User uploads a skin image
2. PHP sends the image to Flask API using cURL
3. Flask processes the image using the AI model
4. Prediction result is returned to PHP
5. Result is displayed on the web interface

---
