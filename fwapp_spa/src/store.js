import { configureStore } from '@reduxjs/toolkit'
import counterReducer from './counterSlice'
import userDetailsReducer from "./userDetailsSlice";

export const store = configureStore({
    reducer: {
        counter: counterReducer,
        userDetails: userDetailsReducer
    },
})
