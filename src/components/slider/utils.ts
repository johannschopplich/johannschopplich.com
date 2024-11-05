/**
 * Clamps a value between a minimum and maximum value.
 *
 * @param value The value to clamp.
 * @param min The minimum value.
 * @param max The maximum value.
 * @returns The clamped value.
 */
export function clamp(value: number, min: number, max: number) {
  return Math.min(Math.max(value, min), max);
}

/**
 * Performs linear interpolation between two values.
 *
 * @param curr The current value.
 * @param dest The destination value.
 * @param factor The factor to interpolate by, between 0 and 1.
 * @returns The interpolated value.
 */
export function lerp(curr: number, dest: number, factor: number) {
  return curr + (dest - curr) * factor;
}

/**
 * Delays a curve by a given factor.
 *
 * @param value The value of the curve to delay.
 * @param offset The factor to delay the curve by.
 * @returns The delayed value of the curve.
 */
export function delay(value: number, offset: number) {
  return clamp(value * (1 + offset) - offset, 0, 1);
}

export function vectorLength(x: number, y: number) {
  return Math.hypot(x, y);
}

export function interpolate(min: number, max: number, ratio: number) {
  return min + ratio * (max - min);
}

export function findClosestIndex(
  target: number,
  steps: number[],
  sorted = false,
) {
  if (steps.length === 0) {
    return -1; // Handle empty array case
  }

  let closestIndex = 0;
  let closestDifference = Math.abs(target - steps[0]);

  for (let i = 1; i < steps.length; i++) {
    const difference = Math.abs(target - steps[i]);

    if (difference < closestDifference) {
      closestIndex = i;
      closestDifference = difference;
    } else {
      // Early exit if the difference starts increasing
      if (sorted) {
        break;
      }
    }
  }

  return closestIndex;
}

function rubberband(distance: number, dimension: number, constant: number) {
  if (dimension === 0 || Math.abs(dimension) === Number.POSITIVE_INFINITY) {
    return distance ** (constant * 5);
  }
  return (distance * dimension * constant) / (dimension + constant * distance);
}

export function rubberbandIfOutOfBounds(
  position: number,
  min: number,
  max: number,
  constant = 0.15,
) {
  if (constant === 0) {
    return clamp(position, min, max);
  }
  if (position < min) {
    return -rubberband(min - position, max - min, constant) + min;
  }
  if (position > max) {
    return +rubberband(position - max, max - min, constant) + max;
  }
  return position;
}
