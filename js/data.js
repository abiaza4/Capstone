const courses = [
    {
        id: 1,
        name: "Python",
        description: "A high-level programming language known for its simplicity and readability.",
        icon: "https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg",
        videos: [
            { title: "Python Full Course for Beginners", youtubeId: "rfscVS0vtbw", views: 24000000, likes: 520000, comments: 28000 },
            { title: "Python Tutorial - Python for Beginners", youtubeId: "kqtD5dpn9C8", views: 18000000, likes: 380000, comments: 22000 },
            { title: "Learn Python - Full Course for Beginners", youtubeId: "agvfruY7Z7w", views: 15000000, likes: 320000, comments: 18000 },
            { title: "Python Crash Course", youtubeId: "JJmcL1N2KAY", views: 12000000, likes: 280000, comments: 15000 },
            { title: "Python Programming Tutorial", youtubeId: "B9nFMZIcQl0", views: 5000000, likes: 120000, comments: 8000 },
            { title: "Advanced Python Tutorial", youtubeId: "QLM3Y3X6dLo", views: 3200000, likes: 85000, comments: 5000 }
        ]
    },
    {
        id: 2,
        name: "Java",
        description: "A versatile, object-oriented programming language used for web and mobile development.",
        icon: "https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg",
        videos: [
            { title: "Java Tutorial for Beginners", youtubeId: "aQatrXwNMjQ", views: 15000000, likes: 340000, comments: 19000 },
            { title: "Learn Java Programming - Full Course", youtubeId: "x4hUNqPNlLg", views: 11000000, likes: 250000, comments: 14000 },
            { title: "Java Programming Tutorial", youtubeId: "Y16XlPpBCgg", views: 8000000, likes: 180000, comments: 9500 },
            { title: "Java Full Course", youtubeId: "hBh_CC5y8-c", views: 6500000, likes: 150000, comments: 8000 },
            { title: "Java Tutorial for Beginners - Complete Course", youtubeId: "T1psV5FRp54", views: 4200000, likes: 95000, comments: 5200 },
            { title: "Object Oriented Programming with Java", youtubeId: "8mGfkTwf0k", views: 3000000, likes: 72000, comments: 4000 }
        ]
    },
    {
        id: 3,
        name: "JavaScript",
        description: "The scripting language for web pages, essential for interactive websites.",
        icon: "https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg",
        videos: [
            { title: "JavaScript Tutorial for Beginners", youtubeId: "W6NZfCO5SIk", views: 22000000, likes: 480000, comments: 26000 },
            { title: "Learn JavaScript - Full Course", youtubeId: "KgL6hqlP0p4", views: 18000000, likes: 390000, comments: 21000 },
            { title: "JavaScript Crash Course", youtubeId: "hdI2bqOjiy4", views: 12000000, likes: 270000, comments: 15000 },
            { title: "JavaScript Programming Tutorial", youtubeId: "C6UhO4P5Rzo", views: 5500000, likes: 130000, comments: 7500 },
            { title: "Modern JavaScript Tutorial", youtubeId: "0S8lMTaVjr4", views: 4800000, likes: 110000, comments: 6200 },
            { title: "JavaScript Fundamentals", youtubeId: "9emXNzqCKyg", views: 3500000, likes: 85000, comments: 4800 }
        ]
    },
    {
        id: 4,
        name: "PHP",
        description: "A server-side scripting language designed for web development.",
        icon: "https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg",
        videos: [
            { title: "PHP Tutorial for Beginners", youtubeId: "OK_JCtrrv-c", views: 12000000, likes: 260000, comments: 14000 },
            { title: "Learn PHP - Full Course", youtubeId: "t0syDUSbddE", views: 9500000, likes: 210000, comments: 12000 },
            { title: "PHP Programming Tutorial", youtubeId: "6E7XmT1rB5w", views: 4200000, likes: 95000, comments: 5500 },
            { title: "PHP Crash Course", youtubeId: "aK7G3aG3w0E", views: 3800000, likes: 88000, comments: 4800 },
            { title: "PHP & MySQL Tutorial", youtubeId: "iC8KbuH_Ms0", views: 3200000, likes: 75000, comments: 4200 },
            { title: "Modern PHP Development", youtubeId: "KdD4lLJpG3M", views: 2800000, likes: 65000, comments: 3600 }
        ]
    },
    {
        id: 5,
        name: "C++",
        description: "A powerful programming language used for system software and performance-critical applications.",
        icon: "https://cdn.jsdelivr.net/gh/devicons/devicon/icons/cplusplus/cplusplus-original.svg",
        videos: [
            { title: "C++ Tutorial for Beginners", youtubeId: "vLnPqwA4Q9g", views: 15000000, likes: 320000, comments: 17000 },
            { title: "Learn C++ - Full Course", youtubeId: "MhYkCHqAT7A", views: 11000000, likes: 240000, comments: 13000 },
            { title: "C++ Crash Course", youtubeId: "G-p2bKDbH7M", views: 6500000, likes: 150000, comments: 8500 },
            { title: "C++ Programming Tutorial", youtubeId: "yG1UbKPEEpQ", views: 4800000, likes: 110000, comments: 6200 },
            { title: "Object Oriented Programming C++", youtubeId: "AB2c6mu1N5E", views: 3500000, likes: 82000, comments: 4500 },
            { title: "C++ Standard Library", youtubeId: "pI1Lj4nV4oM", views: 2200000, likes: 52000, comments: 3000 }
        ]
    },
    {
        id: 6,
        name: "C#",
        description: "A modern, object-oriented language developed by Microsoft for Windows applications.",
        icon: "https://cdn.jsdelivr.net/gh/devicons/devicon/icons/csharp/csharp-original.svg",
        videos: [
            { title: "C# Tutorial for Beginners", youtubeId: "cNfV-ZOROT8", views: 8500000, likes: 190000, comments: 10500 },
            { title: "Learn C# - Full Course", youtubeId: "q_F4n2pyqHM", views: 6200000, likes: 140000, comments: 7800 },
            { title: "C# Programming Tutorial", youtubeId: "pNxMBZBW6QQ", views: 3800000, likes: 88000, comments: 4800 },
            { title: "C# Crash Course", youtubeId: "gfkTfcpWqAY", views: 3200000, likes: 75000, comments: 4200 },
            { title: "C# & Unity Tutorial", youtubeId: "w4zqR_wO7f0", views: 2800000, likes: 65000, comments: 3600 },
            { title: "ASP.NET Core Tutorial", youtubeId: "lE8NqU9-HQQ", views: 2100000, likes: 48000, comments: 2800 }
        ]
    },
    {
        id: 7,
        name: "Ruby",
        description: "A dynamic, reflective programming language focused on simplicity and productivity.",
        icon: "https://cdn.jsdelivr.net/gh/devicons/devicon/icons/ruby/ruby-original.svg",
        videos: [
            { title: "Ruby Tutorial for Beginners", youtubeId: "f6G5ZNTV7Xs", views: 3200000, likes: 75000, comments: 4200 },
            { title: "Learn Ruby - Full Course", youtubeId: "tis5cjAnjT4", views: 2100000, likes: 48000, comments: 2800 },
            { title: "Ruby on Rails Tutorial", youtubeId: "B3FbujgoFWw", views: 1800000, likes: 42000, comments: 2400 },
            { title: "Ruby Programming Tutorial", youtubeId: "D8Y2_WNgQ7Q", views: 1500000, likes: 35000, comments: 2000 },
            { title: "Ruby Crash Course", youtubeId: "k5wEm4Cmp6U", views: 1200000, likes: 28000, comments: 1600 },
            { title: "Ruby on Rails 7 Tutorial", youtubeId: "pPy_Gp2qjqY", views: 1050000, likes: 24000, comments: 1400 }
        ]
    },
    {
        id: 8,
        name: "Swift",
        description: "Apple's programming language for iOS and macOS development.",
        icon: "https://cdn.jsdelivr.net/gh/devicons/devicon/icons/swift/swift-original.svg",
        videos: [
            { title: "Swift Tutorial for Beginners", youtubeId: "comQ1sykzlSY", views: 5200000, likes: 120000, comments: 6800 },
            { title: "Learn Swift - Full Course", youtubeId: "F2aoj6zAt7Q", views: 3800000, likes: 88000, comments: 4800 },
            { title: "iOS Development with Swift", youtubeId: "9A-emr8qV3c", views: 2800000, likes: 65000, comments: 3600 },
            { title: "Swift Programming Tutorial", youtubeId: "Umg_sVWoA7E", views: 2100000, likes: 48000, comments: 2800 },
            { title: "SwiftUI Tutorial", youtubeId: "H9GU7ir2qDM", views: 1800000, likes: 42000, comments: 2400 },
            { title: "Swift 5 Tutorial", youtubeId: "4Gj-cTB02y4", views: 1500000, likes: 35000, comments: 2000 }
        ]
    }
];

function formatNumber(num) {
    if (num >= 1000000) {
        return (num / 1000000).toFixed(1) + 'M';
    } else if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'K';
    }
    return num.toString();
}

function getCourseById(id) {
    return courses.find(c => c.id === parseInt(id));
}

function getVideoThumbnail(youtubeId) {
    return `https://img.youtube.com/vi/${youtubeId}/maxresdefault.jpg`;
}
