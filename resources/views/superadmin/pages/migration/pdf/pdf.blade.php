<!DOCTYPE html>
<html>

    <head>
        <title>Student Masterlist</title>
    </head>

    <body>
        <h1>Section: {{ $section->sectionname }}</h1>
        <h2>Grade: {{ $grade->levelname }}</h2>
        <ul>
            @foreach ($students as $student)
                <li>{{ is_array($student) ? $student['lastname'] : $student->lastname }},
                    {{ is_array($student) ? $student['firstname'] : $student->firstname }}</li>
            @endforeach
        </ul>
    </body>

</html>
