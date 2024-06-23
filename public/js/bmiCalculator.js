function calculateBMI() {
    const height = document.getElementById('height').value;
    const weight = document.getElementById('weight').value;

    if (height && weight) {
        const heightInMeters = height / 100;
        const bmi = weight / (heightInMeters * heightInMeters);
        let category = '';

        if (bmi < 18.5) {
            category = 'Underweight';
        } else if (bmi >= 18.5 && bmi < 24.9) {
            category = 'Normal weight';
        } else if (bmi >= 25 && bmi < 29.9) {
            category = 'Overweight';
        } else {
            category = 'Obese';
        }

        document.getElementById('bmi-result').innerHTML = `Your BMI is ${bmi.toFixed(2)} (${category})`;
    } else {
        document.getElementById('bmi-result').innerHTML = 'Please enter valid height and weight values.';
    }
}
