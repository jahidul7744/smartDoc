Software Requirements Specification (SRS) & Software Design Document (SDD) 

AI-Powered Healthcare Appointment & Diagnosis Management System 

Shape 

Document Information 

Field 

Details 

Project Name 

AI Healthcare Management System 

Document Version 

1.0 

Prepared By 

Senior Business Analyst 

Date 

November 12, 2025 

Purpose 

Complete SRS & SDD Documentation 

Shape 

1. SOFTWARE REQUIREMENTS SPECIFICATION (SRS) 

1.1 Introduction 

1.1.1 Purpose 

This document specifies the functional and non-functional requirements for an AI-powered healthcare management system that connects patients with diagnostic centers and doctors through intelligent symptom analysis and appointment booking. 

1.1.2 Scope 

The system facilitates: 

Patient registration and profile management 

AI-powered symptom analysis and illness prediction 

Doctor discovery based on specialization recommendations 

Appointment scheduling and management 

Digital prescription generation and distribution 

Complete medical history tracking 

1.1.3 Definitions, Acronyms, and Abbreviations 

SRS: Software Requirements Specification 

SDD: Software Design Document 

ML: Machine Learning 

API: Application Programming Interface 

PDF: Portable Document Format 

SMS: Short Message Service 

1.1.4 Intended Audience 

Development Team 

Project Managers 

Quality Assurance Team 

Stakeholders (Diagnostic Centers, Doctors) 

System Administrators 

Shape 

1.2 Overall Description 

1.2.1 Product Perspective 

The system is a web-based healthcare management platform integrating: 

Laravel-based web application 

Python ML microservice for symptom analysis 

MySQL database for data persistence 

Email/SMS notification services 

1.2.2 User Classes and Characteristics 

Patient 

Technical Expertise: Basic to Intermediate 

Primary Functions: Symptom input, appointment booking, prescription retrieval 

Frequency of Use: Occasional (when medical consultation needed) 

Diagnostic Center 

Technical Expertise: Intermediate 

Primary Functions: Facility management, doctor roster management, appointment coordination 

Frequency of Use: Daily 

Doctor 

Technical Expertise: Basic to Intermediate 

Primary Functions: Appointment management, diagnosis entry, prescription creation 

Frequency of Use: Daily 

1.2.3 Operating Environment 

Client-side: Modern web browsers (Chrome, Firefox, Safari, Edge) 

Server-side: Linux/Windows server with PHP 8.x, Python 3.8+ 

Database: MySQL 8.0+ 

Network: Internet connection required 

1.2.4 Design and Implementation Constraints 

Must integrate with external ML API 

Email/SMS gateway integration required 

PDF generation capability required 

Responsive design for mobile/tablet access 

HIPAA/GDPR compliance considerations for health data 

Shape 

1.3 System Features and Requirements 

1.3.1 User Management Module 

FR-UM-001: Patient Registration 

Description: New patients must be able to create accounts. 

Functional Requirements: 

System shall provide registration form with fields: name, email, phone, password, date of birth, gender, address 

System shall validate email uniqueness 

System shall enforce password strength requirements (minimum 8 characters, alphanumeric) 

System shall send verification email upon registration 

System shall store encrypted passwords 

Priority: High 

FR-UM-002: User Authentication 

Description: All users must authenticate to access the system. 

Functional Requirements: 

System shall provide login interface for email/password authentication 

System shall implement session management with timeout (30 minutes) 

System shall provide "Forgot Password" functionality 

System shall support role-based access control (Patient, Doctor, Diagnostic Center) 

Priority: High 

FR-UM-003: Profile Completion Requirement 

Description: Patients must complete profile before accessing core features. 

Functional Requirements: 

System shall detect incomplete patient profiles on login 

System shall redirect to profile completion page 

System shall require: medical history, blood group, allergies, emergency contact 

System shall block access to other modules until profile is complete 

System shall display profile completion progress indicator 

Priority: High 

Shape 

1.3.2 Diagnostic Center Selection Module 

FR-DC-001: Diagnostic Center Listing 

Description: Patients can browse and select diagnostic centers. 

Functional Requirements: 

System shall display list of all active diagnostic centers 

System shall show center details: name, address, contact, ratings, available specializations 

System shall provide search and filter capabilities (location, specialization) 

System shall display distance from patient location (if location services enabled) 

System shall allow sorting by rating, distance, availability 

Priority: High 

FR-DC-002: Center Selection 

Description: Patient selects a diagnostic center to proceed. 

Functional Requirements: 

System shall allow single center selection 

System shall store selected center in patient session 

System shall proceed to symptom input after selection 

Priority: High 

Shape 

1.3.3 AI-Powered Symptom Analysis Module 

FR-AI-001: Symptom Input Interface 

Description: Patients enter their symptoms for AI analysis. 

Functional Requirements: 

System shall provide intuitive symptom input form 

System shall support multiple symptom entry (minimum 1, maximum 10) 

System shall provide autocomplete suggestions for common symptoms 

System shall allow severity rating (1-10 scale) for each symptom 

System shall capture symptom duration 

System shall allow additional notes/description 

Priority: High 

FR-AI-002: ML API Integration 

Description: System integrates with Python ML microservice for illness prediction. 

Functional Requirements: 

System shall send symptom data to Flask/FastAPI endpoint via RESTful API 

System shall format request as JSON payload containing: symptoms array, severity, duration 

System shall implement timeout handling (10 seconds) 

System shall retry failed requests (maximum 3 attempts) 

System shall handle API errors gracefully 

Priority: High 

FR-AI-003: Illness Prediction Display 

Description: Display AI-predicted illness to patient. 

Functional Requirements: 

System shall receive prediction results from ML API 

System shall display predicted illness name 

System shall show confidence score/probability 

System shall display top 3 possible illnesses (if applicable) 

System shall provide disclaimer: "This is AI prediction, not medical diagnosis" 

System shall recommend consulting with doctor 

Priority: High 

FR-AI-004: Doctor Specialization Recommendation 

Description: Recommend appropriate doctor specialization based on prediction. 

Functional Requirements: 

System shall map predicted illness to doctor specializations 

System shall display recommended specialization(s) 

System shall filter available doctors by specialization 

System shall show at least 3 doctors (if available) matching specialization 

Priority: High 

Shape 

1.3.4 Doctor Discovery & Selection Module 

FR-DD-001: Doctor Listing 

Description: Display doctors available at selected diagnostic center. 

Functional Requirements: 

System shall list all doctors associated with selected diagnostic center 

System shall display doctor information: name, specialization, qualifications, experience, ratings, consultation fee 

System shall highlight recommended doctors based on AI prediction 

System shall show real-time availability status 

System shall provide doctor profile details on click 

Priority: High 

FR-DD-002: Doctor Filtering 

Description: Enable filtering of doctor list. 

Functional Requirements: 

System shall provide filters: specialization, availability, rating, consultation fee 

System shall update list dynamically based on filter selection 

System shall maintain at least AI-recommended specialization as default filter 

Priority: Medium 

Shape 

1.3.5 Appointment Booking Module 

FR-AB-001: Schedule Display 

Description: Show available time slots for selected doctor. 

Functional Requirements: 

System shall display doctor's calendar with available slots 

System shall show date picker (next 30 days) 

System shall display time slots in 30-minute intervals 

System shall mark booked slots as unavailable 

System shall highlight available slots 

System shall show doctor's working hours 

Priority: High 

FR-AB-002: Appointment Booking 

Description: Patient books appointment with selected doctor. 

Functional Requirements: 

System shall allow selection of date and time slot 

System shall capture appointment purpose/chief complaint 

System shall display booking summary for confirmation 

System shall validate slot availability before confirmation 

System shall generate unique appointment ID 

System shall update slot status to "booked" upon confirmation 

System shall store appointment details: patient_id, doctor_id, diagnostic_center_id, date, time, symptoms, predicted_illness, status 

Priority: High 

FR-AB-003: Booking Confirmation 

Description: Confirm appointment and send notifications. 

Functional Requirements: 

System shall display success message with appointment details 

System shall send confirmation email to patient with appointment details 

System shall send SMS notification to patient 

System shall notify doctor about new appointment 

System shall notify diagnostic center administration 

System shall provide option to add to calendar (iCal format) 

System shall display appointment confirmation page with printable details 

Priority: High 

Shape 

1.3.6 Doctor Dashboard Module 

FR-DDash-001: Appointment Management 

Description: Doctors view and manage their appointments. 

Functional Requirements: 

System shall display doctor's appointment calendar 

System shall show list view of appointments (today, upcoming, past) 

System shall display patient details for each appointment 

System shall show AI-predicted illness for context 

System shall allow filtering by date, status 

System shall show patient symptoms submitted 

System shall allow appointment status updates: confirmed, completed, cancelled, no-show 

Priority: High 

FR-DDash-002: Patient Medical History 

Description: Doctors access patient medical history. 

Functional Requirements: 

System shall display patient's previous appointments with this doctor 

System shall show past diagnoses and prescriptions 

System shall display patient allergies and medical conditions 

System shall show patient vitals history 

System shall allow chronological and reverse-chronological sorting 

Priority: High 

Shape 

1.3.7 Diagnosis & Prescription Module 

FR-DP-001: Diagnosis Entry 

Description: Doctor enters diagnosis after consultation. 

Functional Requirements: 

System shall provide diagnosis entry form for each appointment 

System shall capture: final diagnosis, clinical notes, recommended tests, follow-up required 

System shall allow multiple diagnoses selection 

System shall timestamp diagnosis entry 

System shall mark appointment as "completed" after diagnosis submission 

Priority: High 

FR-DP-002: Prescription Creation 

Description: Doctor creates digital prescription. 

Functional Requirements: 

System shall provide prescription form with fields:  

Medicine name (autocomplete from drug database) 

Dosage 

Frequency (e.g., twice daily) 

Duration (days) 

Instructions (e.g., after meals) 

System shall allow multiple medicines addition 

System shall provide general instructions field 

System shall include follow-up date 

System shall validate prescription data 

Priority: High 

FR-DP-003: Prescription PDF Generation 

Description: Generate prescription in PDF format. 

Functional Requirements: 

System shall generate PDF containing:  

Diagnostic center letterhead 

Doctor name, qualifications, registration number 

Patient name, age, gender 

Appointment date 

Symptoms reported 

Diagnosis 

Prescribed medicines with dosage and instructions 

General advice 

Follow-up date 

Doctor's digital signature 

System shall maintain prescription template 

System shall generate unique prescription ID 

System shall store PDF in system 

Priority: High 

FR-DP-004: Prescription Distribution 

Description: Send prescription to patient. 

Functional Requirements: 

System shall automatically email prescription PDF to patient upon generation 

System shall send SMS with prescription ready notification 

System shall allow patient to download prescription from portal 

System shall maintain prescription in patient's medical records 

Priority: High 

Shape 

1.3.8 Notification Module 

FR-NOT-001: Email Notifications 

Description: Send email notifications for key events. 

Functional Requirements: 

System shall send emails for:  

Registration confirmation 

Appointment booking confirmation 

Appointment reminder (24 hours before) 

Prescription ready notification 

Appointment cancellation 

System shall use email templates 

System shall log email delivery status 

Priority: High 

FR-NOT-002: SMS Notifications 

Description: Send SMS notifications for key events. 

Functional Requirements: 

System shall send SMS for:  

Appointment confirmation 

Appointment reminder (2 hours before) 

Prescription ready notification 

System shall integrate with SMS gateway 

System shall log SMS delivery status 

Priority: Medium 

Shape 

1.3.9 Medical History & Follow-up Module 

FR-MH-001: Patient Medical Records 

Description: Maintain comprehensive patient medical history. 

Functional Requirements: 

System shall store all consultations chronologically 

System shall display appointment history with dates, doctors, diagnoses 

System shall provide access to all past prescriptions 

System shall show test results (if uploaded) 

System shall allow patient to download entire medical history 

Priority: High 

FR-MH-002: Follow-up Management 

Description: Manage follow-up appointments. 

Functional Requirements: 

System shall allow doctors to mark follow-up required 

System shall send follow-up reminders to patients 

System shall provide easy re-booking for follow-up appointments 

System shall link follow-up appointments to original consultation 

Priority: Medium 

Shape 

1.4 Non-Functional Requirements 

1.4.1 Performance Requirements 

NFR-PERF-001: Response Time 

System shall load pages within 3 seconds under normal load 

ML API prediction shall complete within 5 seconds 

Database queries shall execute within 1 second 

NFR-PERF-002: Scalability 

System shall support 10,000 concurrent users 

System shall handle 1,000 appointments per day per diagnostic center 

Database shall efficiently handle 1 million patient records 

NFR-PERF-003: Availability 

System shall maintain 99.5% uptime 

Planned maintenance windows shall be scheduled during low-traffic hours 

1.4.2 Security Requirements 

NFR-SEC-001: Data Encryption 

System shall encrypt passwords using bcrypt 

System shall use HTTPS for all communications 

System shall encrypt sensitive medical data at rest 

NFR-SEC-002: Authentication & Authorization 

System shall implement role-based access control 

System shall enforce session timeout after 30 minutes inactivity 

System shall log all access attempts 

NFR-SEC-003: Data Privacy 

System shall comply with healthcare data protection regulations 

System shall implement data anonymization for analytics 

System shall provide patient consent management 

1.4.3 Usability Requirements 

NFR-USE-001: User Interface 

System shall provide intuitive, user-friendly interface 

System shall be responsive for mobile, tablet, desktop 

System shall support accessibility standards (WCAG 2.1) 

NFR-USE-002: Learning Curve 

New users shall complete registration within 5 minutes 

Patients shall book appointment within 3 clicks after login 

1.4.4 Reliability Requirements 

NFR-REL-001: Error Handling 

System shall gracefully handle ML API failures 

System shall provide meaningful error messages 

System shall implement automatic retry mechanisms 

NFR-REL-002: Data Backup 

System shall perform daily automated backups 

System shall maintain backup retention for 90 days 

1.4.5 Maintainability Requirements 

NFR-MAIN-001: Code Quality 

Code shall follow PSR-12 coding standards for PHP 

Code shall maintain minimum 70% test coverage 

System shall use version control (Git) 

NFR-MAIN-002: Documentation 

System shall maintain updated API documentation 

System shall document all database schemas 

System shall maintain user manuals 

 