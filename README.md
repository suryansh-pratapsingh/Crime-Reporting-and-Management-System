# E-viva – Smart Viva Evaluation System


E-viva is an AI-powered web application designed to automate and simplify the viva (oral exam) process for both faculty and students. It leverages multiple text similarity algorithms to assess student answers in real-time, providing instant and transparent feedback.

## 🚀 Features

- 👨‍🏫 Two User Roles: Student and Faculty
- 🎓 Random Question Selection for Each Viva
- ⚡ Instant Evaluation Using 5 Text Matching Algorithms
- 📊 Accuracy Score by:  
  - Exact String Matching  
  - Token-Based Partial Matching  
  - Levenshtein Distance  
  - Cosine Similarity  
  - NLP-Based Semantic Similarity
- 🔐 Secure Login System
- 📁 Faculty Dashboard to Upload Questions & View Student Results

## 🧩 Tech Stack

- 🌐 Frontend: HTML, CSS, Bootstrap, JavaScript
- 🧠 Backend: PHP
- 🗄️ Database: MySQL
- 🔍 AI/NLP: Levenshtein, Cosine, Token Matching, NLP Semantic Models

## 🗃️ Database Schema

- users (name, email, password, role)
- questions (faculty_id, question_text, correct_answer)
- student_viva_session (student_id, timestamp)
- student_answers (session_id, question_id, answer, accuracy scores...)





