export const localStorageClient = {
  getItem: (key) => {
    if (typeof window !== 'undefined') {
      const item = window.localStorage.getItem(key)
      return item ? JSON.parse(item) : undefined
    }
  },

  setItem: (key, value) => {
    if (typeof window !== 'undefined') {
      localStorage.setItem(key, JSON.stringify(value))
    }
  },

  removeItem: (key) => {
    if (typeof window !== 'undefined') {
      localStorage.removeItem(key)
    }
  },

  clear: () => {
    if (typeof window !== 'undefined') {
      localStorage.clear()
    }
  },
}
