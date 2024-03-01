// DOM elements
const DOMstrings = {
  stepsBtnClass: 'multisteps-form__progress-btn',
  stepsBtns: document.querySelectorAll('.multisteps-form__progress-btn'),
  stepsBar: document.querySelector('.multisteps-form__progress'),
  stepsForm: document.querySelector('.multisteps-form__form'),
  stepsFormTextareas: document.querySelectorAll('.multisteps-form__textarea'),
  stepFormPanelClass: 'multisteps-form__panel',
  stepFormPanels: document.querySelectorAll('.multisteps-form__panel'),
  stepPrevBtnClass: 'js-btn-prev',
  stepUpdateBtnClass: 'js-btn-update',
  stepNextBtnClass: 'js-btn-next',
  stepPlusBtnClass: 'js-btn-plus',
  stepSaveBtnClass: 'js-btn-save',
  stepChooseBtnClass: 'js-btn-choose',

};

document.addEventListener('DOMContentLoaded', function() {
  const firstStepBtn = document.querySelector('.multisteps-form__progress-btn.first');

  if (!firstStepBtn) {
    return;
  }

  firstStepBtn.classList.add('js-active');

  const firstPanel = document.querySelector('.multisteps-form__panel.first');

  if (!firstPanel) {
    return;
  }

  const firstStepIndex = Array.from(DOMstrings.stepsBar.children).indexOf(firstStepBtn);
  console.log("First Step Index:", firstStepIndex);

  setActiveStep(firstStepIndex);
  setActivePanel(firstStepIndex);
  setFormHeight();
});

// Remove class from a set of items
const removeClasses = (elemSet, className) => {
  elemSet.forEach(elem => {
    elem.classList.remove(className);
  });
};

// Return exact parent node of the element
const findParent = (elem, parentClass) => {
  let currentNode = elem;
  while (currentNode && !currentNode.classList.contains(parentClass)) {
    currentNode = currentNode.parentNode;
  }
  return currentNode;
};

// Get active button step number
// const getActiveStep = elem => {
//   return Array.from(DOMstrings.stepsBtns).indexOf(elem);
// };

// Set all steps before clicked (and clicked too) to active
const setActiveStep = activeStepNum => {
  removeClasses(DOMstrings.stepsBtns, 'js-active');
  DOMstrings.stepsBtns.forEach((elem, index) => {
    if (index <= activeStepNum) {
      elem.classList.add('js-active');
    }
  });
};

// Get active panel
const getActivePanel = () => {
  let activePanel;
  DOMstrings.stepFormPanels.forEach(elem => {
    if (elem.classList.contains('js-active')) {
      activePanel = elem;
    }
  });
  return activePanel;
};

// Open active panel (and close inactive panels)
const setActivePanel = activePanelNum => {
  removeClasses(DOMstrings.stepFormPanels, 'js-active');
  DOMstrings.stepFormPanels.forEach((elem, index) => {
    if (index === activePanelNum) {
      elem.classList.add('js-active');
      setFormHeight(elem);
    }
  });
};

const formHeight = activePanel => {
  if (activePanel && activePanel.offsetHeight !== undefined) {
    const activePanelHeight = activePanel.offsetHeight;
    DOMstrings.stepsForm.style.height = `${activePanelHeight}px`;
  }
};


const setFormHeight = () => {
  const activePanel = getActivePanel();
  formHeight(activePanel);
};

// Steps Bar click function
DOMstrings.stepsBar.addEventListener('click', e => {
  const eventTarget = e.target;
  if (!eventTarget.classList.contains(`${DOMstrings.stepsBtnClass}`)) {
    return;
  }
  const activeStep = getActiveStep(eventTarget);
  setActiveStep(activeStep);
  setActivePanel(activeStep);
});

// Prev/Next Buttons click
DOMstrings.stepsForm.addEventListener('click', e => {
  const eventTarget = e.target;
  if (!(eventTarget.classList.contains(`${DOMstrings.stepPrevBtnClass}`) || eventTarget.classList.contains(`${DOMstrings.stepNextBtnClass}`) || 
  eventTarget.classList.contains(`${DOMstrings.stepPlusBtnClass}`) )) {
    return;
  }

  e.preventDefault();

  if (eventTarget.classList.contains(`${DOMstrings.stepPlusBtnClass}`)) {
    const panelTambah = document.getElementById('panel_tambah');
    let panelTambahIndex = Array.from(DOMstrings.stepFormPanels).indexOf(panelTambah);

    setActivePanel(panelTambahIndex);
    return;
  }


  
  // Pastikan $(document).ready() berada di luar blok kondisional


  
  const activePanel = findParent(eventTarget, `${DOMstrings.stepFormPanelClass}`);
  let activePanelNum = Array.from(DOMstrings.stepFormPanels).indexOf(activePanel);
  if (eventTarget.classList.contains(`${DOMstrings.stepPrevBtnClass}`)) {
    activePanelNum--;
  } else {
    activePanelNum++;
  }
  setActiveStep(activePanelNum);
  setActivePanel(activePanelNum);
});

// Setting proper form height onload
window.addEventListener('load', setFormHeight, false);

// Setting proper form height onresize
window.addEventListener('resize', setFormHeight, false);

// Changing animation via animation select
const setAnimationType = newType => {
  DOMstrings.stepFormPanels.forEach(elem => {
    elem.dataset.animation = newType;
  });
};

const animationSelect = document.querySelector('.pick-animation__select');

// Pastikan elemen ditemukan sebelum menambahkan event listener
if (animationSelect) {
    animationSelect.addEventListener('change', () => {
        const newAnimationType = animationSelect.value;
        setAnimationType(newAnimationType);
    });
}



// DOMstrings.stepsForm.addEventListener('click', e => {
//   const eventTarget = e.target;

//    if (!(eventTarget.classList.contains(`${DOMstrings.stepPlusBtnClass}`) )) {
//     return;
//   }
//   const panelTambah = document.getElementById('panel_tambah');
//   let panelTambahIndex = Array.from(DOMstrings.stepFormPanels).indexOf(panelTambah);

//   if (eventTarget.classList.contains(`${DOMstrings.stepPlusBtnClass}`)) {

//     setActiveStep(panelTambahIndex);
//     setActivePanel(panelTambahIndex);

    
//   }
// });

