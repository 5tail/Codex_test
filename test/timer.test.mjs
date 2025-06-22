import test from 'node:test';
import assert from 'node:assert';

// Minimal DOM stubs
global.document = {
  elements: {},
  getElementById(id) {
    if (!this.elements[id]) {
      this.elements[id] = { textContent: '', className: '', value: '', checked: false, addEventListener() {} };
    }
    return this.elements[id];
  },
  body: { addEventListener() {} }
};

const timer = await import('../timer.js');

function withFakeNow(start, cb) {
  const original = Date.now;
  let now = start;
  Date.now = () => now;
  try {
    cb(v => now = v);
  } finally {
    Date.now = original;
  }
}

test('inspectionStart resets each solve', () => {
  withFakeNow(0, setNow => {
    timer.startInspection();
    setNow(16000);
    timer.computePenalty();
    assert.equal(timer.getPenalty(), '+2');
    timer.stopInspection();

    timer.startInspection();
    setNow(30000); // 14s after new start
    timer.computePenalty();
    assert.equal(timer.getPenalty(), '');
    timer.stopInspection();
  });
});

test('computePenalty respects DNF threshold', () => {
  withFakeNow(0, setNow => {
    timer.startInspection();
    setNow(18000);
    timer.computePenalty();
    assert.equal(timer.getPenalty(), 'DNF');
    timer.stopInspection();

    timer.startInspection();
    setNow(34000); // 16s after new start
    timer.computePenalty();
    assert.equal(timer.getPenalty(), '+2');
    timer.stopInspection();
  });
});
