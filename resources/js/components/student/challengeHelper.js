import { formatDateDash } from '../../helper.js';
export function isChallengeCompleted(student, challenge) {
    if (challenge.challenge_type === 1) {
      const grades = student.studentGrades.filter(grade =>
        grade.subject_id === challenge.subject_id &&
        grade.grade_date <= challenge.end_date
      );
      const sum = grades.reduce((total, grade) => total + parseInt(grade.grade), 0);
      const average = sum / grades.length;
      return average >= challenge.minimum_grade;
    }
    if (challenge.challenge_type === 2) {
        const requiredCount = challenge.grade_count;
        const grades = student.studentGrades.filter(
            (grade) => {
                const gradeValue = parseInt(grade.grade);
                const meetsCondition = grade.subject_id === challenge.subject_id &&
                                        gradeValue >= challenge.minimum_grade &&
                                        grade.grade_date >= challenge.start_date &&
                                        grade.grade_date <= challenge.end_date;
                return meetsCondition;
            }
        );
        return grades.length >= requiredCount;
    }
    if (challenge.challenge_type === 3) {
        const today = formatDateDash(new Date());
        if(today >= challenge.end_date) {
            const hasGradeN = student.studentGrades.some(grade => {
            const meetsCondition = grade.grade_date >= challenge.start_date &&
                                    grade.grade_date <= challenge.end_date &&
                                    grade.grade === 'n';
            return meetsCondition;
        });
        return !hasGradeN;
        }
    }
    return false;
  }
  
  export async function makeChallengeCompleted(student, challenge) {
    const studentChallenge = challenge.student_challenges.find(
      sc => sc.student_id === student.id
    );
    if (studentChallenge && !studentChallenge.completed) {
      studentChallenge.completed = true;
      try {
        await axios.put(`/api/student/challenges/${studentChallenge.id}`, studentChallenge);
      } catch (error) {
        console.error("Error marking challenge as completed:", error);
      }
    }
  }
  