const express = require('express');
const bodyParser = require('body-parser');
const nodemailer = require('nodemailer');
const cors = require('cors');

const app = express();
const port = 3000;

app.use(cors());
app.use(bodyParser.json());

app.post('/send-email', (req, res) => {
    const { email, firstName, lastName } = req.body;

    const transporter = nodemailer.createTransport({
        service: 'gmail', // Utiliza el servicio que prefieras
        auth: {
            user: 'corujotobiass@gmail.com',
            pass: 'buuf fyml pyof kqjq'
        }
    });

    const mailOptions = {
        from: 'corujotobiass@gmail.com',
        to: email,
        subject: 'Bienvenido a Moovika',
        text: `Hola ${firstName} ${lastName},\n\n¡Bienvenido a Moovika! Nos alegra que te unas a nuestra comunidad.`
    };

    transporter.sendMail(mailOptions, (error, info) => {
        if (error) {
            console.log(error);
            res.status(500).send('Error al enviar el correo electrónico');
        } else {
            console.log('Correo electrónico enviado: ' + info.response);
            res.status(200).send('Correo electrónico enviado');
        }
    });
});

app.listen(port, () => {
    console.log(`Servidor escuchando en http://localhost:${port}`);
});
