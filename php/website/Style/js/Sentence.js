/*!
 * Project: LG_NewUi
 * Author: Ki
 * Last Updated: 2024-10-18
 * Description: 小手一牵 岁岁年年
 * 
 * Copyright (c) 2022-2024 by Ki. All Rights Reserved.
 * 
 * License: This software is licensed for both personal and commercial use under specific terms.
 * Redistribution, modification, or resale without explicit written permission is strictly prohibited.
 * Unauthorized use, copying, or redistribution will result in immediate termination of support and updates.
 * 
 * Documentation and License: https://lguiy.kikiw.cn/wiki
 * 
 * * Warning: This is an original work, and all copyright belongs to LG_NewUi.
 * Any attempt to scrape, copy, sell, or redistribute this software without permission is strictly forbidden.
 * Violators will face immediate legal action, blacklisting, and permanent cessation of future updates.
 * 
 * Development requires substantial time and effort. We appreciate your respect for the work involved and your support.
 */

let timer = null; 
let isPrinting = false; 

function displayTextOneByOne(text, element, speed) {
    let index = 0;

    function printCharacter() {
        if (index < text.length) {
            element.textContent += text[index];
            index++;
            timer = setTimeout(printCharacter, speed);
        } else {
            isPrinting = false;
        }
    }

    printCharacter();
}

function getRandomQuoteAndDisplay(speed, testText) {
    const quoteElement = document.getElementById('random-quote');

    if (isPrinting) {
        clearTimeout(timer);
        isPrinting = false;
    }

    quoteElement.textContent = '';
    
    setTimeout(function() {
        isPrinting = true; 
        displayTextOneByOne(testText, quoteElement, speed);
    }, 1500);
}
