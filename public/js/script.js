//DOM elements
const DOMstrings = {
  stepsBtnClass: 'multisteps-form__progress-btn',
  stepsBtns: document.querySelectorAll(`.multisteps-form__progress-btn`),
  stepsBar: document.querySelector('.multisteps-form__progress'),
  stepsForm: document.querySelector('.multisteps-form__form'),
  stepsFormTextareas: document.querySelectorAll('.multisteps-form__textarea'),
  stepFormPanelClass: 'multisteps-form__panel',
  stepFormPanels: document.querySelectorAll('.multisteps-form__panel'),
  stepPrevBtnClass: 'js-btn-prev',
  stepNextBtnClass: 'js-btn-next',
  stepSimpanBtnClass:'js-btn-simpan',
  btnAdd: document.querySelector('.js-btn-add'),
  btnBack: document.querySelector('.js-btn-back'),
  btnKembali: document.querySelector('.js-btn-kembali'),
  additionalFormContainer: document.getElementById('additionalFormContainer'),  
  
};



//remove class from a set of items
const removeClasses = (elemSet, className) => {
  
  elemSet.forEach(elem => {
    
    elem.classList.remove(className);
    
  });
  
};

//return exect parent node of the element
const findParent = (elem, parentClass) => {
  
  let currentNode = elem;

  while(! (currentNode.classList.contains(parentClass))) {
    currentNode = currentNode.parentNode;
  }
  
  return currentNode;
  
};

//get active button step number
const getActiveStep = elem => {
  return Array.from(DOMstrings.stepsBtns).indexOf(elem);
};

//set all steps before clicked (and clicked too) to active
const setActiveStep = (activeStepNum) => {
  
  //remove active state from all the state
  removeClasses(DOMstrings.stepsBtns, 'js-active');
  
  //set picked items to active
  DOMstrings.stepsBtns.forEach((elem, index) => {
    
    if(index <= (activeStepNum) ) {
      elem.classList.add('js-active');
    }
    
  });
};



//get active panel
const getActivePanel = () => {
  
  let activePanel;
  
  DOMstrings.stepFormPanels.forEach(elem => {
    
    if(elem.classList.contains('js-active')) {
      
      activePanel = elem;
      
    }
    
  });
  
  return activePanel;
                                    
};

//open active panel (and close unactive panels)
const setActivePanel = activePanelNum => {
  
  //remove active class from all the panels
  removeClasses(DOMstrings.stepFormPanels, 'js-active');
  
  //show active panel
  DOMstrings.stepFormPanels.forEach((elem, index) => {
    if(index === (activePanelNum)) {
      
      elem.classList.add('js-active');
   
      setFormHeight(elem);
      
    }
  })
  
};

//set form height equal to current panel height
const formHeight = (activePanel) => {
  
  const activePanelHeight = activePanel.offsetHeight;
  
  DOMstrings.stepsForm.style.height = `${activePanelHeight}px`;
  
};
const setFormHeight = () => {
  const activePanel = getActivePanel();

  formHeight(activePanel);
}



//STEPS BAR CLICK FUNCTION
// Event listener untuk klik pada langkah-langkah (stepsBar)
DOMstrings.stepsBar.addEventListener('click', e => {
  const eventTarget = e.target;
  
  if(!eventTarget.classList.contains(`${DOMstrings.stepsBtnClass}`)) {
    return;
  }
  
  const activeStep = getActiveStep(eventTarget);
  setActiveStep(activeStep);
  setActivePanel(activeStep);
});

// Event listener untuk klik pada tombol PREV/NEXT (stepsForm)
DOMstrings.stepsForm.addEventListener('click', e => {
  const eventTarget = e.target;
  
  if(!((eventTarget.classList.contains(`${DOMstrings.stepPrevBtnClass}`)) ||  eventTarget.classList.contains(`${DOMstrings.stepSimpanBtnClass}`) || (eventTarget.classList.contains(`${DOMstrings.stepNextBtnClass}`)))) {
    return;
  }
  e.preventDefault();
  if (eventTarget.classList.contains(`${DOMstrings.stepSimpanBtnClass}`)) {
    // Setelah berhasil disimpan, lanjutkan dengan berpindah ke panel lain
    const panelOrderList = document.getElementById('table_id');
    let panelOrderListIndex = Array.from(DOMstrings.stepFormPanels).indexOf(panelOrderList);

    setActiveStep(panelOrderListIndex);
    setActivePanel(panelOrderListIndex);
    return;
}
  
  const activePanel = findParent(eventTarget, `${DOMstrings.stepFormPanelClass}`);
  let activePanelNum = Array.from(DOMstrings.stepFormPanels).indexOf(activePanel);
  

  if(eventTarget.classList.contains(`${DOMstrings.stepPrevBtnClass}`)) {
    activePanelNum--;
  } else {
    activePanelNum++;
  }
  
  setActiveStep(activePanelNum);
  setActivePanel(activePanelNum);
});



 
DOMstrings.btnAdd.addEventListener('click', () => {
  // Nonaktifkan semua tombol progres kecuali "Barang Dipinjam"
  const progressButtons = document.querySelectorAll('.multisteps-form__progress-btn');
  progressButtons.forEach(button => {
    button.disabled = true;
  });

  DOMstrings.stepsForm.style.display = 'none';

  // Tampilkan form tambahan di dalam card
 
  DOMstrings.additionalFormContainer.style.display = 'block';

 
});

DOMstrings.btnBack.addEventListener('click', () => {
  // Nonaktifkan semua tombol progres kecuali "Barang Dipinjam"
  const progressButtons = document.querySelectorAll('.multisteps-form__progress-btn');
  progressButtons.forEach(button => {
    button.disabled = false;
  });

  DOMstrings.stepsForm.style.display = 'block';

  // Tampilkan form tambahan di dalam card
 
  DOMstrings.additionalFormContainer.style.display = 'none';

 
});







//SETTING PROPER FORM HEIGHT ONLOAD
window.addEventListener('load', setFormHeight, false);

//SETTING PROPER FORM HEIGHT ONRESIZE
window.addEventListener('resize', setFormHeight, false);

//changing animation via animation select !!!YOU DON'T NEED THIS CODE (if you want to change animation type, just change form panels data-attr)

const setAnimationType = (newType) => {
  DOMstrings.stepFormPanels.forEach(elem => {
    elem.dataset.animation = newType;
  })
};


//selector onchange - changing animation
const animationSelect = document.querySelector('.pick-animation__select');

animationSelect.addEventListener('change', () => {
  const newAnimationType = animationSelect.value;
  
  setAnimationType(newAnimationType);
});


// DOMstrings.btnSimpan.addEventListener('click', function(e) {
//   e.preventDefault();

//   const form = this.closest('form'); // Find the closest form
//   const url = form.getAttribute('action');
//   const method = form.getAttribute('method');
//   const data = new FormData(form);

//   // You can customize the data before sending if needed
//   // For example, you can add additional data to FormData object

//   // Perform Ajax request
//   $.ajax({
//     type: method,
//     url: url,
//     data: data,
//     processData: false,
//     contentType: false,
//     success: function(response) {
//       // Handle success
//       console.log('Form submitted successfully!');

//       // After successful submission, move to the next step
//       const activePanel = findParent(DOMstrings.btnSimpan, `${DOMstrings.stepFormPanelClass}`);
//       let activePanelNum = Array.from(DOMstrings.stepFormPanels).indexOf(activePanel) + 1;

//       setActiveStep(activePanelNum);
//       setActivePanel(activePanelNum);
//     },
//     error: function(xhr) {
//       // Handle error
//       console.error('Error submitting form:', xhr.responseText);
//     }
//   });
// });